<style>
  .q-item{
    color: #222d32;
    font-size: 15px;
  }
</style>
@extends('layouts.app')
@section('title', 'Questions')
@section('breadcrumb')
  <li>Questionnaires</li>
  <li>{{ $survey->title }}</li>
  <li>Groupes</li>
  <li title="{{$groupe->name}}">{{str_limit($groupe->name, 30)}}</li>
  <li>Questions</li>
@endsection
@section('content')
  <section class="content showQuestion">
    <div class="row">
      <div class="col-md-4">
        <div class="box box-primary">
          <div class="box-body">
            <p class="">
              <a href="javascript:void(0)" onclick="return chmSurvey.show({id: {{$survey->id}} })"> <i class="fa fa-eye"></i> Preview </a> |
              <a href="{{ url('surveys/'.$survey->id.'/groupes') }}" > <i class="fa fa-list"></i> Types </a> |
              <a href="{{ url('config/surveys') }}"> <i class="fa fa-list"></i> Questionnaires </a>
            </p>
            <div class="accordion" id="accordion2">
              <div class="accordion-group">
                <div class="accordion-heading">
                  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse{{$groupe->id}}" aria-expanded="true">
                    {{ $groupe->name }}
                  </a>
                  <a href="javascript:void(0)" onclick="return chmQuestion.create({gid: {{$groupe->id}} })" class="icon-add-question" data-toggle="tooltip" title="Ajouter une question pour ce groupe"> <i class="fa fa-plus btn-success circle-icon"></i> </a>
                </div>
                <div id="collapse{{$groupe->id}}" class="accordion-body collapse in">
                  <div class="accordion-inner">

                    @if(count($groupe->questions)>0)
                      <ul class="list-group">
                        @foreach($groupe->questions as $q)
                          @if($q->parent_id == 0)
                            <li class="list-group-item">
                              <a href="{{url('surveys/'.$survey->id.'/groupes/'.$groupe->id.'/questions/'.$q->id)}}" title="{{ $q->titre }}" class="{{ $q->parent_id == 0 ? 'q-item':'' }}">{{ str_limit($q->titre, 40) }}</a>
                              {{ csrf_field() }}
                              <a href="javascript:void(0)" onclick="return chmModal.confirm('', 'Supprimer la question ?', 'Etes-vous sur de vouloir supprimer cette question ?','chmQuestion.delete', {sid: {{$survey->id}} ,gid: {{$groupe->id}}, qid:{{$q->id}} }, {width: 450})" class="text-red circle-icon pull-right" data-toggle="tooltip" title="Supprimer"> <i class="fa fa-trash"></i> </a>
                              <a href="javascript:void(0)" onclick="return chmQuestion.edit({ sid: {{$survey->id}}, gid: {{$groupe->id}}, qid:{{$q->id}} })" class="text-yellow circle-icon pull-right" data-toggle="tooltip" title="Editer"> <i class="glyphicon glyphicon-pencil"></i> </a>
                            </li>
                          @endif
                        @endforeach
                      </ul>
                    @else
                      <p class="help-block"> Aucune question</p>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
