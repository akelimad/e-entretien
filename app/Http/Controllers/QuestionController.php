<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Question;
use App\Groupe;
use App\Answer;
use Auth;

class QuestionController extends Controller
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
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($gid, Request $request)
    {
        ob_start();
        $parent_id = $request->parent_id;
        echo view('questions.form', compact('gid', 'parent_id'));
        $content = ob_get_clean();
        return ['title' => 'Ajouter une question', 'content' => $content];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(empty($request->parent_id)){
            $question = new Question();
            $question->titre = $request->titre;
            $question->type = $request->type;
            $question->parent_id = $request->parent_id ? $request->parent_id : 0;
            $question->groupe_id = $request->groupe_id;
            $question->save();
        }else{
            foreach ($request->subQuestions as $sub_q) {
                $question = new Question();
                $question->titre = $sub_q;
                $question->parent_id = $request->parent_id;
                $question->groupe_id = $request->groupe_id;
                $question->save();
            }
        }
        $url=url('groupes/'.$request->groupe_id.'/questions/'.$question->id);
        $request->session()->flash('success', "La question à été ajouté avec suucès. <a href='{$url}'>cliquer ici pour la consulter</a>");
        if($question->save()) {
            return ["status" => "success", "message" => 'Les informations ont été sauvegardées avec succès.'];
            redirect('users');
        } else {
            return ["status" => "warning", "message" => 'Une erreur est survenue, réessayez plus tard.'];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($gid, $qid)
    {
        $groupes = Groupe::all();
        $gr = Groupe::findOrFail($gid);
        $qs = Question::findOrFail($qid);
        return view('questions.show', compact('groupes', 'gr', 'qs'));
    }

    public function preview()
    {
        $groupes = Groupe::all();
        return view('questions.preview', compact('groupes'));
    }

    public function survey()
    {
        $groupes = Groupe::all();
        return view('questions.survey', compact('groupes'));
    }

    public function survey2()
    {
        $groupes = Groupe::all();
        // dd(Answer::getAnswers(1));
        return view('questions.survey2', compact('groupes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}