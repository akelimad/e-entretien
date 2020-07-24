@extends('layouts.app')
@section('style')
  <style>
    .objectifs-type .nav-tabs>li>a {
      padding: 10px 50px;
    }
    .objectifs-type .nav-tabs>li.active>a,
    .objectifs-type .nav-tabs>li.active>a:focus,
    .objectifs-type .nav-tabs>li.active>a:hover {
      border: none;
      border-bottom: 3px solid #3c8dbc;
      color: #3c8dbc;
    }
    table tbody tr td {
      padding: 15px 8px !important;
    }
  </style>
@endsection
@section('content')
  <section class="content objectifs">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary card">
          <h3 class="mb40"> Liste des objectifs pour: {{$e->titre}} - {{ $user->name." ".$user->last_name }} </h3>

          <div class="nav-tabs-custom">
            @include('partials.tabs')
            <div class="tab-content">
              <div class="box-body no-padding mb40">
                <form action="{{ route('updateNoteObjectifs') }}" method="post">
                  <input type="hidden" name="entretien_id" value="{{$e->id}}">
                  <input type="hidden" name="user_id" value="{{$user->id}}">
                  {{ csrf_field() }}
                  <div class="row">
                    <div class="col-md-12 objectifs-type">
                      <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#personnel">Personnel</a></li>
                        <li><a data-toggle="tab" href="#team">Equipe</a></li>
                      </ul>
                      <div class="tab-content pt-30">
                        <div id="personnel" class="tab-pane fade in active">
                          @php($total = 0)
                          @forelse($objectifsPersonnal as $objectif)
                            <div class="item">
                              <p class="bg-gray p-5">
                                <b>Titre :</b> {{ $objectif->title }}
                                <span class="pull-right font-20">{{ \App\Objectif::getTotalNote($e->id, $user->id, $objectif->id) }} %</span>
                              </p>
                            </div>
                            <div class="item">
                              <p><b>Date d'échéance :</b> {{ date('d/m/Y', strtotime($objectif->deadline)) }}</p>
                            </div>
                            <div class="item">
                              <p class="mb-0"><b>Indicateurs :</b></p>
                              <table class="table">
                                <thead>
                                <tr>
                                  <th width="28%">Titre</th>
                                  <th width="10%" class="text-center">Objectif fixé</th>
                                  <th width="50%" class="text-center">Réalisé</th>
                                  <th width="50%" class="text-center">En %</th>
                                  <th width="12%" class="text-center">Pondération %</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($objectif->getIndicators() as $indicator)
                                  <tr>
                                    <td>{{ $indicator['title'] }}</td>
                                    <td class="text-center">{{ $indicator['fixed'] }}</td>
                                    <td>
                                      <input type="text"
                                             class="slider"
                                             name="objectifs[{{ $objectif->id }}][{{ $indicator['id'] }}][realized]"
                                             data-provide="slider"
                                             data-slider-min="0"
                                             data-slider-max="{{ $indicator['fixed'] * 2 }}"
                                             data-slider-step="1"
                                             data-slider-value="{{ \App\Objectif_user::getRealised($e->id, $user->id, $objectif->id, $indicator['id']) }}"
                                             data-slider-tooltip="always">
                                    </td>
                                    <td class="text-center">
                                      {{ $total += (\App\Objectif_user::getRealised($e->id, $user->id, $objectif->id, $indicator['id']) / $indicator['fixed']) * 100 }}
                                    </td>
                                    <td class="text-center">{{ $indicator['ponderation'] }}</td>
                                  </tr>
                                @endforeach
                                </tbody>
                              </table>
                            </div>
                          @empty
                            <tr>
                              <td>
                                @include('partials.alerts.info', ['messages' => "Aucun résultat trouvé" ])
                              </td>
                            </tr>
                          @endforelse
                        </div>
                        <div id="team" class="tab-pane fade">
                          @forelse($objectifsTeam as $objectif)
                            <div class="item">
                              <p class="bg-gray p-5"><b>Titre :</b> {{ $objectif->title }}</p>
                            </div>
                            <div class="item">
                              <p><b>Equipe :</b> {{ $objectif->team > 0 ? \App\Team::find($objectif->team)->name : '---' }}</p>
                            </div>
                            <div class="item">
                              <p><b>Date d'échéance :</b> {{ $objectif->deadline }}</p>
                            </div>
                            <div class="item">
                              <p class="mb-0"><b>Indicateurs :</b></p>
                              <table class="table">
                                <thead>
                                <tr>
                                  <th width="28%" class="text-center">Titre</th>
                                  <th width="10%" class="text-center">Objectif fixé</th>
                                  <th width="50%" class="text-center">
                                    Réalisé <span title="Cette valeur ne peut être remplie que par les managers" data-toggle="tooltip"><i class="fa fa-question-circle font-16"></i></span>
                                  </th>
                                  <th width="12%" class="text-center">Pondération %</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($objectif->getIndicators() as $indicator)
                                  <tr>
                                    <td>{{ $indicator['title'] }}</td>
                                    <td class="text-center">{{ $indicator['fixed'] }}</td>
                                    <td>
                                      <input type="text"
                                             class="slider"
                                             name="objectifs[{{ $objectif->id }}][{{ $indicator['id'] }}][]"
                                             data-provide="slider"
                                             data-slider-min="0"
                                             data-slider-max="{{ $indicator['fixed'] * 2 }}"
                                             data-slider-step="1"
                                             data-slider-value=""
                                             data-slider-tooltip="always"
                                             data-slider-enabled="{{ $user->id != Auth::user()->id }}">
                                    </td>
                                    <td class="text-center">{{ $indicator['ponderation'] }}</td>
                                  </tr>
                                @endforeach
                                </tbody>
                              </table>
                            </div>
                          @empty
                            <tr>
                              <td>
                                @include('partials.alerts.info', ['messages' => "Aucun résultat trouvé" ])
                              </td>
                            </tr>
                          @endforelse
                        </div>
                        <div class="save-action">
                          <button type="submit" class="btn btn-success pull-right" > <i class="fa fa-check"></i> Enregistrer tout</button>
                          <div class="clearfix"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="callout callout-info">
            <p class="">
              <i class="fa fa-info-circle fa-2x"></i>
              <span class="content-callout">Cette page affiche la liste des objectifs du collaborateur: <b>{{ $user->name." ".$user->last_name }}</b> dans le cadre de l'entretien : <b>{{ $e->titre }}</b> </span>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('javascript')
  <script>
    $(document).ready(function () {
      // Calculate sub total & total in real-time
      var userTypes
      @if ($user->id == Auth::user()->id)
        userTypes = ['user']
      @else
        userTypes = ['user', 'mentor']
      @endif
      $.each(userTypes, function (i, userType) {
        $('.slider').on('change', function (ev) {
          var objectifId = $(this).data('objectif')
          var sectionId = $(this).data('section')
          var subObjectifs = $('.'+ userType +'SubObjSection-' + objectifId)
          var total = sectionTotal = SubTotalObjectif = SubTotalSubObjectif = note = ponderation = objPonderation = 0

          // calculate sub total of objectif that have own sub objectifs
          $.each(subObjectifs, function (i, el) {
            note = $(el).closest('.subObjectifRow').find('.' + userType + 'Note').val()
            ponderation = $(el).closest('.subObjectifRow').find('.ponderation').text()
            SubTotalSubObjectif += parseInt(note) * parseInt(ponderation)
          })
          objPonderation = $('[data-'+ userType +'objrow="'+ objectifId +'"]').find('.ponderation').text()
          objPonderation = parseInt(objPonderation) / 100
          SubTotalSubObjectif = Math.round((SubTotalSubObjectif / 100) * objPonderation)
          $('#'+ userType +'SubTotalSubObjectif-' + objectifId).text(SubTotalSubObjectif)

          // calculate sub total of objectif that have'nt sub objectifs
          $.each($('#' + userType + 'ObjectifRow-' + objectifId), function (i, el) {
            note = $(el).closest('.objectifRow').find('.' + userType + 'Note').val()
            ponderation = $(el).closest('.objectifRow').find('.ponderation').text()
            SubTotalObjectif += parseInt(note) * (parseInt(ponderation) / 100)
          })
          SubTotalObjectif = Math.round(SubTotalObjectif)
          $('#'+ userType +'SubTotalObjectif-' + objectifId).text(SubTotalObjectif)

          // calculate sub total of section
          var countObjs = 0
          $.each($('.'+ userType +'SubTotalObjectif-' + sectionId), function (i, el) {
            countObjs += 1
            sectionTotal += parseInt($(el).text())
          })
          sectionTotal = sectionTotal / countObjs
          $('#' + userType + 'SubTotalSection-' + sectionId).text(Math.round(sectionTotal))

          // calculate total note of evaluation
          var countSections = 0
          $.each($('.' + userType + 'SubTotalSection'), function (i, el) {
            countSections += 1
            total += parseInt($(el).text())
          })
          total = total / countSections
          $('.' + userType + 'TotalNote').text(Math.round(total))
        })
      })
      $('.slider').trigger('change')
    })
  </script>
@endsection


