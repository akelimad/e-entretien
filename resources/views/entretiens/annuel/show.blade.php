@extends('layouts.app')
@section('content')
    <section class="content evaluations">
        <div class="row">
            <div class="col-md-12">
                <div class="card box box-primary">
                    <h3 class="mb40"> Détails de l'entretien annuel d'évaluation: {{$e->titre}} </h3>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#" >Synthèse</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/activites')}}" >Evaluation</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/activites')}}" >Carrières</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/skills')}}" >Compétences</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/objectifs')}}" >Objectifs</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/formations')}}" >Formations</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/remunerations')}}">Salaire</a></li>
                            <li><a href="{{url('entretiens/'.$e->id.'/comments')}}">Commentaire</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Date de l'entretien : </label>
                                    <div class="col-md-9"> {{ Carbon\Carbon::parse($e->date)->format('d/m/Y')}} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Entretien à clôturer avant fin : </label>
                                    <div class="col-md-9"> {{ Carbon\Carbon::parse($e->date_limit)->format('d/m/Y')}} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Validé par le collaborateur :</label>
                                    <div class="col-md-9"> <span class="label label-success">oui</span> </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Validé par le mentor :</label>
                                    <div class="col-md-9"> <span class="label label-success">oui</span> </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Mentor :</label>
                                    <div class="col-md-9"> {{Auth::user()->name." ".Auth::user()->last_name}} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Titre :</label>
                                    <div class="col-md-9"> {{ $e->titre }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Collaborateur(trice) évalué(e) :</label>
                                    <div class="col-md-9"> {{ $u->name." ".$u->last_name }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Société :</label>
                                    <div class="col-md-9"> {{ $u->society ? $u->society : '---' }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Adresse email :</label>
                                    <div class="col-md-9"> 
                                        <a href="mailto:{{ $u->email }}">{{ $u->email }}</a> <a href="{{url('notifyUserInterview')}}" class="btn btn-primary"> <i class="fa fa-envelope"></i> envoyer-le un email pour l'informer</a>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Fonction :</label>
                                    <div class="col-md-9"> {{ $u->function ? $u->function :'---' }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Service :</label>
                                    <div class="col-md-9"> {{ $u->service ? $u->service : '---' }} </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <a href="{{url('/')}}" class="btn btn-default"> Annuler </a>
                                    <a onclick="return chmEntretien.edit({e_id:{{ $e->id }}})" class="btn btn-success"> Mettre à jour </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  