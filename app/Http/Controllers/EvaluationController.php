<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Entretien;
use App\Evaluation;
use App\Groupe;
use App\Survey;
use App\User;

class EvaluationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($e_id, $uid)
    {
        $entretien = Entretien::findOrFail($e_id);
        $evaluations = Entretien::findEvaluations($entretien);
        $sid = Evaluation::surveyId($e_id, 1);
        $survey = Survey::findOrFail($sid);
        $groupes = $survey->groupes;
        $user = User::findOrFail($uid);
        return view('evaluations.index', [
            'evaluations' => $evaluations, 
            'survey' => $survey, 
            'e'=> $entretien,  
            'groupes' => $groupes, 
            'user' => $user
        ]);
    }

}