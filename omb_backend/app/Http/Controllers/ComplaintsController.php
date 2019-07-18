<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Carbon;

use App\Complaint;
use App\CaseFile;
use App\Tracing;
use App\Procedure;
use App\Resolution;
use App\Survey;
use App\User;
use App\ComplaintType;

class ComplaintsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $complaints = Complaint::orderBy('created_at', 'desc')->get();
        
        return response()->json(['complaints' => $complaints]);
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'creator' => 'required', 
            'contact_phone' => 'required', 
            'contact_available' => 'required', 
            'description' => 'required'
        ]);
        
        $user = User::firstOrCreate(['email' => $request->creator['email']], $request->creator);
        
        $complaint = new Complaint($request->all());
        $complaint->created_by = $user->id;
        $complaint->folio = Complaint::whereYear('created_at', now()->year)->count() + 1; 
        $complaint->save();
        $complaint->refresh()->notif();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $complaint = Complaint::with(['procedure', 'accused', 'tracings'])->findOrFail($id);
        
        $types = ComplaintType::all();
        
        return response()->json([
            'complaint' => $complaint, 
            'types' => $types
        ]);
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'creator' => 'required', 
            'contact_phone' => 'required', 
            'contact_available' => 'required', 
            'description' => 'required'
        ]);
        
        $complaint = Complaint::findOrFail($id);
        $complaint->fill($request->all());
        $complaint->status_id = 1;
        $complaint->save();
    }
    
    public function setReview(Request $request, $id) {
        $request->validate([
            'interview' => 'required', 
            'type' => 'required'
        ]);
        
        $complaint = Complaint::findOrFail($id);
        $complaint->type_id = $request->type; 
        $complaint->status_id = 2; 
        
        $meeting_date = $request->interview['date'] . " " . $request->interview['time'];
        
        if(!Tracing::where('meeting_date', $meeting_date)->first()){
        
            $metting = Tracing::updateOrCreate(['complaint_id' => $id, 'type' => 'ENTREVISTA', 'subject' => 'INICIAL'], ['meeting_date' => $meeting_date]);
        
            $complaint->save();
            $complaint->refresh()->notif();
            
        }else{
            return response()->json(['message' => "Existe otra cita programada"], 400);
        }
        
    }
    
    public function setStatement(Request $request, $id) {
        $request->validate([
            'user' => 'required', 
            'facts' => 'required', 
            'frequency' => 'required',
            'date_since' => 'required|date',
            'date_until' => 'nullable|date',
            'place' => 'required',
            'attitude' => 'required',
            'reaction' => 'required',
            'made_changes' => 'required',
            'affectation' => 'required'
        ]);
        
        if(auth()->user()->isAdmin())
            $complaint = Complaint::findOrFail($id);
        else
            $complaint = Complaint::where('created_by', auth()->user()->id)->findOrFail($id);
        
        $accused = User::updateOrCreate(['email' => $request->user['email']], $request->user);
        
        $complaint->fill($request->all());
        $complaint->status_id = 3; 
        $complaint->accused_id = $accused->id;
        $complaint->save();
        
        return response()->json(['complaint' => $complaint->with('tracings')->first()]);
    }
    
    public function getResolutions() {
        $resolutions = Resolution::all();
        
        return response()->json(['resolutions' => $resolutions]);
    }
    
    public function setClosing(Request $request, $id) {
        $request->validate([
            'procedure' => 'required'
        ]);
        
        if(auth()->user()->isAdmin()){
            $complaint = Complaint::findOrFail($id);
            $procedure = Procedure::updateOrCreate(['complaint_id' => $id], $request->procedure);
            $complaint->procedure()->save($procedure);
            $complaint->status_id = 5;
            $complaint->resolution_id = $request->resolution_id;
            $complaint->resolution_date = now();
            $complaint->save();
            $complaint->refresh()->notif();
            
            return response()->json(['complaint' => $complaint]);
        }
        
    }
    
    public function getReport() {
        $report = Complaint::with(['accused', 'procedure', 'resolution'])->orderBy('created_at')->get();
        
        return response()->json(['report' => $report]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $complaint = Complaint::where('created_by', auth()->user()->id)->findOrFail($id);
        $complaint->delete();
    }
}
