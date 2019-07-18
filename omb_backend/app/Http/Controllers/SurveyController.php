<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Complaint;
use App\Survey;
use App\Question;

class SurveyController extends Controller
{
    public function getSurvey($id) {
        if(!Survey::where('complaint_id', $id)->first()){
            $survey = Question::all();
            return response()->json(['survey' => $survey]);
        }
    }
    
    public function setSurvey(Request $request, $id) {
        foreach($request->all() as $question => $answer){
            if(isset($answer)){
                $survey = Survey::updateOrCreate(['complaint_id' => $id, 'question_id' => $question], 
                    [
                        'scale' => (is_numeric($answer) && $answer < 5 ? $answer : null), 
                        'comments' => (!is_numeric($answer) ? $answer : null)
                    ]);
            }
        }
    }
}
