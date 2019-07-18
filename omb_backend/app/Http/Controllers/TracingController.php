<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Complaint;
use App\Tracing;
use App\User;
use Illuminate\Support\Carbon;

class TracingController extends Controller
{
    
    public function index() {
        $events = Tracing::orderBy('meeting_date')->get();
        
        return response()->json(['events' => $events]);
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
            'date' => 'required|date', 
            'time' => 'required',
            'subject' => 'required', 
            'type' => 'required'
        ]);
        
        $meeting_date = ($request->date . " " . $request->time);
        
        if(!Tracing::where('meeting_date', $meeting_date)->first()){
        
            $tracing = new Tracing($request->all());
            $tracing->meeting_date = Carbon::parse($meeting_date);
            $tracing->save();
            $complaint = Complaint::find($request->complaint_id);
            $complaint->status_id = 4; 
            $complaint->save();
            
            $tracing->refresh()->notif();

            return response()->json(['tracing' => $tracing]);
        }else{
            return response()->json([
                'message' => "Ya existe otra cita agendada"
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tracing = Tracing::findOrFail($id);
        
        return response()->json(['tracing' => $tracing]);
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
        $tracing = Tracing::findOrFail($id);
        $tracing->fill($request->all());
        $tracing->save();
        $tracing->refresh()->notif();
        
        return response()->json(['tracing' => $tracing]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tracing = Tracing::findOrFail($id);
        $tracing->delete();
    }
    
    public function search($id) {
        
        $tracings = Tracing::where('complaint_id', $id)->orderBy('meeting_date')->get();
        
        return response()->json(['tracings' => $tracings]);
    }
}
