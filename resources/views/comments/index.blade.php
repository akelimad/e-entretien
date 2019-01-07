@extends('layouts.app')
@section('title', 'Commentaires')
@section('content')
    <section class="content evaluations">
        <div class="row">
            <div class="col-md-12">
                @if(Session::has('mentor_comment'))
                    @include('partials.alerts.success', ['messages' => Session::get('mentor_comment') ])
                @endif
                <div class="box box-primary direct-chat direct-chat-warning card">
                    <h3 class="mb40"> Liste des commentaires pour: {{ $e->titre }} - {{ $user->name." ".$user->last_name }} </h3>
                    <div class="nav-tabs-custom">
                        @include('partials.tabs')
                        <div class="tab-content">
                            @if($comment)
                                <!-- {{--<div class="box-body table-responsive no-padding mb40">--}}
                                    {{--<div class="col-md-6">--}}
                                        {{--<h4 class="alert alert-info">{{ $user->name." ".$user->last_name }}</h4>--}}
                                        {{--<div class="comment-box coll">{{ $comment->userComment or '---' }}</div>--}}

                                        {{--@if($user->id == Auth::user()->id && !App\Entretien::answered($e->id, $user->id))--}}
                                            {{--<a href="javascript:void(0)" onclick="return chmComment.edit({eid: {{$e->id}}, uid: {{$user->id}}, cid: {{$comment->id}} })" class="btn-warning icon-fill" data-toggle="tooltip" title="Editer votre commentaire"> <i class="glyphicon glyphicon-pencil"></i> </a>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}
                                    {{--<div class="col-md-6">--}}
                                        {{--<h4 class="alert alert-info">{{ $user->parent->name." ".$user->parent->last_name }}</h4>--}}
                                        {{--@if($user->id != Auth::user()->id && App\Entretien::answeredMentor($e->id, $user->id, $user->parent->id) == false)--}}
                                            {{--<form action="{{ url('entretiens/'.$e->id.'/u/'.$user->id.'/commentaires/'.$comment->id.'/mentorUpdate') }}" method="post">--}}
                                                {{--{{ csrf_field() }}--}}
                                                {{--{{ method_field('PUT') }}--}}
                                                {{--<textarea name="mentorComment" class="form-control" required style="min-height: 200px;">{{ $comment->mentorComment }}</textarea>--}}
                                                {{--<p></p>--}}
                                                {{--<button type="submit" class="btn-info icon-fill pull-right" data-toggle="tooltip" title="Repondez sur le commentaire de votre collaborateur"><i class="fa fa-paper-plane"></i> </button>--}}
                                            {{--</form>--}}
                                        {{--@else--}}
                                            {{--<div class="comment-box mentor">{{ $comment->mentorComment }}</div>--}}
                                        {{--@endif--}}
                                    {{--</div>--}}
                                {{--</div>--}} -->
                                <div class="box-body">
                                    <div class="direct-chat-messages" style="height: auto;">
                                        <div class="col-md-6">
                                            <div class="direct-chat-msg mb20">
                                                <div class="direct-chat-info clearfix">
                                                    <span class="direct-chat-name pull-left">{{ $user->name." ".$user->last_name }}</span>
                                                    <span class="direct-chat-timestamp pull-right">{{ Carbon\Carbon::parse($comment->created_at)->format('d/m/Y à H:i')}}</span>
                                                </div>
                                                <img class="direct-chat-img" src="{{ App\User::avatar($user->id) }}" alt="message user image">
                                                <div class="direct-chat-text">
                                                    {{ $comment->userComment or '---' }}
                                                    @if($user->id == Auth::user()->id && !App\Entretien::answered($e->id, $user->id))
                                                        <a href="javascript:void(0)" onclick="return chmComment.edit({eid: {{$e->id}}, uid: {{$user->id}}, cid: {{$comment->id}} })" class="btn-warning icon-fill" data-toggle="tooltip" title="Editer votre commentaire"> <i class="glyphicon glyphicon-pencil"></i> </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @if(($user->id == Auth::user()->id && App\Entretien::answeredMentor($e->id, $user->id, $user->parent->id)) or ($user->id != Auth::user()->id))
                                        <div class="col-md-6">
                                            <div class="direct-chat-msg right">
                                                <div class="direct-chat-info clearfix">
                                                    <span class="direct-chat-name pull-right">{{ $user->parent->name." ".$user->parent->last_name }}</span>
                                                    <span class="direct-chat-timestamp pull-left">{{ $comment->mentor_updated_at != null ? Carbon\Carbon::parse($comment->mentor_updated_at)->format('d/m/Y à H:i') : '' }}</span>
                                                </div>
                                                <img class="direct-chat-img" src="{{ App\User::avatar($user->parent->id) }}" alt="message user image">
                                                @if($user->id != Auth::user()->id && $comment->mentorComment == '' && !App\Entretien::answeredMentor($e->id, $user->id, $user->parent->id))
                                                    <form action="{{ url('entretiens/'.$e->id.'/u/'.$user->id.'/commentaires/'.$comment->id.'/mentorUpdate') }}" method="post">
                                                        {{ csrf_field() }}
                                                        {{ method_field('PUT') }}
                                                        <textarea name="comment" class="form-control" required style="min-height: 130px;" placeholder="Ecrivez votre commentaire ...">{{ $comment->mentorComment }}</textarea>
                                                        <p></p>
                                                        <button type="submit" class="btn-info icon-fill pull-right" data-toggle="tooltip" title="Repondez sur le commentaire de votre collaborateur"><i class="fa fa-paper-plane"></i> </button>
                                                    </form>
                                                @else
                                                    <div class="direct-chat-text">
                                                        {{ $comment->mentorComment ? $comment->mentorComment : '----' }}
                                                        @if(!App\Entretien::answeredMentor($e->id, $user->id, $user->parent->id))
                                                        <a href="javascript:void(0)" onclick="return chmComment.edit({eid: {{$e->id}}, uid: {{$user->parent->id}}, cid: {{$comment->id}} })" class="btn-warning icon-fill" data-toggle="tooltip" title="Editer votre commentaire"> <i class="glyphicon glyphicon-pencil"></i> </a>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée ... !!" ])
                            @endif
                            <a href="{{url('/')}}" class="btn btn-default"><i class="fa fa-long-arrow-left"></i> Retour</a>
                            @if(!App\Entretien::answered($e->id, $user->id) && Auth::user()->id == $user->id)
                                <buton onclick="return chmModal.confirm('', 'Soumettre ?', 'Attention !! Vous n’aurez plus la possibilité de modifier votre évaluation. Êtes-vous sur de vouloir soumettre ?','chmEntretien.submission', {eid: {{$e->id}}, user: {{$user->id}}}, {width: 450, btnlabel: 'Soumettre'})" class="btn btn-danger"><i class="fa fa-check"></i> Soumettre</buton>
                            @endif
                            @if(!App\Entretien::answeredMentor($e->id, $user->id, $user->parent->id) && Auth::user()->id != $user->id)
                                <buton onclick="return chmModal.confirm('', 'Soumettre ?', 'Attention !! Vous n’aurez plus la possibilité de modifier votre évaluation. Êtes-vous sur de vouloir soumettre ?','chmEntretien.submission', {eid: {{$e->id}}, user: {{$user->id}}}, {width: 450, btnlabel: 'Soumettre'})" class="btn btn-danger"><i class="fa fa-check"></i> Soumettre</buton>
                            @endif
                            @if(!App\Entretien::answered($e->id, $user->id) && !$comment)
                                <a onclick="return chmComment.create({eid: {{$e->id}}, uid:{{$user->id}} })" class="btn btn-success"><i class="fa fa-plus"></i> Ajouter un commentaire</a>
                            @endif
                        </div>
                    </div>
                    <div class="callout callout-info">
                        <p class="">
                            <i class="fa fa-info-circle fa-2x"></i> 
                            <span class="content-callout">Cette page affiche le commentaires de la part du collaborateur: <b>{{ $user->name." ".$user->last_name }}</b> pour l'entretien: <b>{{ $e->titre }}</b> </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  