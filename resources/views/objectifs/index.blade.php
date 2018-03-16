@extends('layouts.app')
@section('content')

    <section class="content evaluations">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary card">
                    <h3 class="mb40"> La liste des objectifs </h2>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li><a href="{{url('entretiens/'.$e->id.'/u/'.$user->id)}}">Synthèse</a></li>
                            @foreach($evaluations as $evaluation)
                            <li class="{{ Request::segment(5) == $evaluation->title ? 'active':'' }}">
                                <a href="{{url('entretiens/'.$e->id.'/u/'.$user->id.'/'.$evaluation->title)}}">{{ $evaluation->title }}</a>
                            </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @if(count($objectifs)>0)
                                <div class="box-body table-responsive no-padding mb40">
                                    <form action="{{url('objectifs/updateNoteObjectifs')}}">
                                        <table class="table table-hover table-bordered table-inversed-blue">
                                            <tr>
                                                <th>Critères d'évaluation</th>
                                                <th>Note</th>
                                                <th>Apréciation</th>
                                                <th>Pondération(%) </th>
                                                <th>Objectif N+1 </th>
                                            </tr>
                                            @foreach($objectifs as $objectif)
                                                <input type="hidden" name="parentObjectif[]" value="{{$objectif->id}}">
                                                <tr>
                                                    <td colspan="5" class="objectifTitle"> 
                                                        {{ $objectif->title }} 
                                                    </td>
                                                </tr>
                                                @foreach($objectif->children as $sub)
                                                <tr>
                                                    <td>{{ $sub->title }}</td>
                                                    <td class="criteres text-center">
                                                        <input type="text" id="slider" name="objectifs[{{$objectif->id}}][{{$sub->id}}][]" data-provide="slider" data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="{{App\Objectif::getObjectif($sub->id) ? App\Objectif::getObjectif($sub->id)->note : '0' }}" data-slider-tooltip="always" required >
                                                        <input type="hidden" name="subObjectifIds[{{$objectif->id}}][]" value="{{$sub->id}}">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="objectifs[{{$objectif->id}}][{{$sub->id}}][]" class="form-control" value="{{App\Objectif::getObjectif($sub->id) ? App\Objectif::getObjectif($sub->id)->appreciation : '' }}">
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $sub->ponderation }}
                                                        <input type="hidden" name="objectifs[{{$objectif->id}}][{{$sub->id}}][]" value="{{$sub->ponderation}}">
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="objectifs[{{$objectif->id}}][{{$sub->id}}][]" {{isset(App\Objectif::getObjectif($sub->id)->objNplus1) && App\Objectif::getObjectif($sub->id)->objNplus1 == 1 ? 'checked':''}}>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="5" class="sousTotal"> 
                                                        Sous-total  <span class="badge badge-success pull-right">{{$objectif->sousTotal ? $objectif->sousTotal : 0.00}}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="5" class="btn-warning">
                                                    TOTAL DE L'ÉVALUATION  
                                                    <span class="btn btn-info pull-right">{{ $total }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="btn-danger">
                                                    NOTE FINALE  
                                                    <span class="btn btn-info pull-right"> {{ $total * 10 }} % </span>
                                                </td>
                                            </tr>
                                        </table>
                                        <input type="submit" value="Sauvegarder" class="btn btn-success">
                                    </form>
                                    {{ $objectifs->links() }}
                                </div>
                            @else
                                @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  

