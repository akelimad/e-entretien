<style>
  .text-center {
    text-align: center;
  }
  .table {
    width: 100%;
    max-width: 100%;
    border-spacing: 0;
    border-collapse: collapse;
  }
  .table tr td,
  .table tr th {
    padding: 8px;
    vertical-align: middle;
    border: 1px solid #ddd;
  }
  .row {
    width: 100%;
    margin-right: -15px;
    margin-left: -15px;
  }
  .col-md-2 {
    width: 16.66666667%;
  }
  .col-md-6 {
    width: 50%;
  }
  .col-md-10 {
    width: 83.33333333%;
  }
  .col-md-2, .col-md-6, .col-md-10 {
    float: left;
  }
  .section-title {
    background-color: #0b8ccd;
    color: white;
    width: 100%;
    font-size: 18px;
    padding: 5px;
    font-weight: bold;
  }
  .mt-20 {
    margin-top: 20px;
  }
  .clearfix {
    clear: both;
  }
  .q-choice {
    border: 1px solid slategray;
    padding: 10px;
  }
  .groupTitle {
    padding: 10px;
    background: lightslategray;
    margin-bottom: 20px;
  }
  .questionTitle {
    margin-bottom: 20px;
    font-weight: 600;
  }
  .pull-right {
    float: right;
  }
  tr.sous-total {
    background-color: darkseagreen;
  }
  tr.total {
    background-color: greenyellow;
  }
</style>
{{-- ****************** Header ********************** --}}
<h2 class="text-center">Formulaire d'Evaluation Annuelle des performances	</h2>
<table class="table">
  <tbody>
    <tr>
      <td>Nom et prénom :</td>
      <td>{{ $user->last_name . ' ' .$user->name }}</td>
      <td>Manager :</td>
      <td>{{ $user->parent->last_name . ' ' .$user->parent->name }}</td>
    </tr>
    <tr>
      <td>Poste occupé :</td>
      <td>{{ is_numeric($user->function) ? App\Fonction::find($user->function)->title : '---' }}</td>
      <td>Entité :</td>
      <td>{{ is_numeric($user->service) ? App\Department::find($user->service)->title : '---' }}</td>
    </tr>
  </tbody>
</table>
{{-- ****************** Infos de l'évaluation ********** --}}
<div class="entretien-infos">
  <h3 class="text-center" style="color: #0b8ccd;font-weight: 600">{{ $e->titre }}</h3>
  <div class="col-md-6">La période évaluée commence le, {{ Carbon\Carbon::parse($e->start_periode)->format('d/m/Y')}}</div>
  <div class="col-md-6">La période évaluée finit le, {{ Carbon\Carbon::parse($e->end_periode)->format('d/m/Y')}}</div>
</div>
{{-- ****************** Evaluations ********************** --}}
<div class="mt-20"><p class="section-title">{{ $survey->title }}</p></div>
<div class="evaluation-survey">
  @if(!empty($survey->groupes))
    <div class="mentor-item">
      <div class="panel-group">
        @php($gNote = 0)
        @php($c = 0)
        @foreach($survey->groupes as $g)
          @php($c += 1)
          @if(count($g->questions)>0)
            <div class="panel panel-info">
              <div class="panel-heading">
                <div class="groupTitle">{{ $g->name }}</div>
                @if ($g->notation_type == 'section')
                  @if(!App\Entretien::answeredMentor($e->id, $user->id,App\User::getMentor($user->id)->id))
                    @if(App\Evaluation::findOrFail($g->survey->evaluation_id)->title == 'Evaluations')
                      <input type="text" data-group-source="{{$g->id}}" class="notation inputNote" min="1"
                             max="{{App\Setting::get('max_note')}}" placeholder="Note"
                             value="{{App\Answer::getGrpNote($g->id, $user->id, $e->id) ? App\Answer::getGrpNote($g->id, $user->id, $e->id):''}}"
                             @if($g->notation_type == 'section' && App\Evaluation::findOrFail($g->survey->evaluation_id)->title == 'Evaluations') style="display: block;"
                             required @else style="display: none;" @endif>
                    @endif
                    @if(App\Answer::getGrpNote($g->id, $user->id, $e->id))
                      @php($gNote += App\Answer::getGrpNote($g->id, $user->id, $e->id))
                    @endif
                  @else
                    <span class="pull-right">Note : {{App\Answer::getGrpNote($g->id, $user->id, $e->id) ? App\Answer::getGrpNote($g->id, $user->id, $e->id):''}}
                      / {{App\Survey::countGroups($survey->id)}}</span>
                    @if(App\Answer::getGrpNote($g->id, $user->id, $e->id))
                      @php($gNote += App\Answer::getGrpNote($g->id, $user->id, $e->id))
                    @endif
                  @endif
                @endif
              </div>
              <div class="panel-body">
                @forelse($g->questions as $q)
                  <div class="form-group">
                    @if(in_array($q->type, ['text', 'textarea', 'radio']))
                      <input type="text" data-group-target="{{$g->id}}" name="answers[{{$q->id}}][note]"
                             placeholder="Note" class="notation inputNote" size="3" min="1"
                             max="{{App\Setting::get('max_note')}}"
                             value="{{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) ? App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->note : ''}}"
                             @if($g->notation_type == 'item' && App\Evaluation::findOrFail($g->survey->evaluation_id)->title == 'Evaluations') style="display: block;"
                             required @endif>
                    @endif
                    @if($q->parent == null)
                      <div class="questionTitle"><i class="fa fa-caret-right"></i>
                        {{$q->titre}}
                      </div>
                    @endif
                    @if($q->type == 'text')
                      <input type="{{$q->type}}" name="answers[{{$q->id}}][ansr]" class="form-control" required
                             value="{{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) ? App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer : ''}}" {{ (App\Entretien::answeredMentor($e->id, $user->id,App\User::getMentor($user->id)->id)) == false ? '':'disabled' }}>
                    @elseif($q->type == 'textarea')
                      <textarea name="answers[{{$q->id}}][ansr]" class="form-control"
                                required {{ (App\Entretien::answeredMentor($e->id, $user->id,App\User::getMentor($user->id)->id)) == false ? '':'disabled' }}>{{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) ? App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer : ''}}</textarea>
                    @elseif($q->type == "checkbox")
                      <input type="text" data-group-target="{{$g->id}}" name="answers[{{$q->id}}][note]"
                             class="notation" min="1" max="{{App\Setting::get('max_note')}}"
                             value="{{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) ? App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->note : ''}}"
                             style="display: {{$g->notation_type == 'item' && App\Evaluation::findOrFail($g->survey->evaluation_id)->title == 'Evaluations' ? 'block':'none'}}">
                      <p class="help-inline text-red checkboxError"><i class="fa fa-close"></i> Veuillez cocher au
                        moins un élement</p>
                      @foreach($q->children as $child)
                        <div class="survey-checkbox">
                          <input type="{{$q->type}}" name="answers[{{$q->id}}][ansr][]" id="{{$child->titre}}" value="{{$child->id}}" {{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) && in_array($child->id, json_decode(App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer)) ? 'checked' : '' }} {{ (App\Entretien::answeredMentor($e->id, $user->id,App\User::getMentor($user->id)->id)) == false ? '':'disabled' }}>
                          <label for="{{$child->titre}}">{{ $child->titre }}</label>
                        </div>
                      @endforeach
                      <div class="clearfix"></div>
                    @elseif($q->type == "radio")
                      @foreach($q->children as $child)
                        <input type="{{$q->type}}" name="answers[{{$q->id}}][ansr]" id="{{$child->id}}" value="{{$child->id}}" required="" {{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) && $child->id == App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer ? 'checked':'' }} {{ (App\Entretien::answeredMentor($e->id, $user->id,App\User::getMentor($user->id)->id)) == false ? '':'disabled' }}>
                        <label for="{{$child->id}}">{{ $child->titre }}</label>
                      @endforeach
                    @elseif($q->type == "slider")
                      <div class="" style="margin-top: 30px;">
                        <span>{{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) ? App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer : ''}}</span>
                      </div>
                    @elseif ($q->type == "rate")
                      @foreach($q->children as $child)
                        <div class="q-choice">
                          <div class="col-md-2">
                            <input type="radio" name="answers[{{$q->id}}][ansr]" value="{{ $child->id }}" id="mentor-{{ $child->id }}" {{ App\Answer::getMentorAnswers($q->id, $user->id, $e->id) && App\Answer::getMentorAnswers($q->id, $user->id, $e->id)->mentor_answer == $child->id ? 'checked' : '' }}> {{ $child->titre }}
                          </div>
                          <div class="col-md-10">
                            {{ json_decode($child->options)->label }}
                          </div>
                          <div class="clearfix"></div>
                        </div>
                      @endforeach
                    @endif
                  </div>
                @empty
                  <p class="help-block">Aucune question</p>
                @endforelse
              </div>
            </div>
          @endif
        @endforeach
      </div>
      @if(App\Entretien::note($e->id, $user->id) > 0)
        <div class="callout callout-success" style="margin-top:15px">
          <p class="">
            <i class="fa fa-info-circle fa-2x"></i>
              <span class="content-callout h4"><b style="margin-right: 1em;">Note globale : {{App\Entretien::note($e->id, $user->id)}}</b>
                @foreach(App\Answer::NOTE_DEGREE as $key => $value)
                  <span class="fa fa-star {{$key <= App\Entretien::note($e->id, $user->id) ? 'checked':''}}" title="{{$value['title'].' ('.$value['ref'].')'}}" data-toggle="tooltip"></span>
                @endforeach
              </span>
          </p>
        </div>
      @endif
    </div>
  @else
    <p class="alert alert-default">Aucune donnée disponible !</p>
  @endif
</div>

{{-- ****************** Objectifs ********************** --}}
<div class="mt-20"><p class="section-title">Objectifs</p></div>
@if(!empty($objectifs)>0)
  <table class="table">
    <tr>
      <th width="25%">Critères d'évaluation</th>
      <th class="text-center">Pondération (%)</th>
      <th width="15%">Mentor Notation (%)</th>
      <th>Mentor Appreciation</th>
    </tr>
    @php($c = 0)
    @php($userTotal = 0)
    @php($mentorTotal = 0)
    @foreach($objectifs as $objectif)
      @php($c += 1)
      <tr>
        <td colspan="4" class="text-center">{{ $objectif->title }}</td>
      </tr>
      @php($usersousTotal = 0)
      @php($mentorsousTotal = 0)
      @php($sumPonderation = 0)
      @foreach($objectif->children as $sub)
        @php( $sumPonderation += $sub->ponderation )
        @if($user->id == Auth::user()->id )
          @if(App\Objectif::getObjectif($e->id,$user->id, $sub->id))
            @php( $usersousTotal += App\Objectif::getObjectif($e->id,$user->id, $sub->id)->userNote * $sub->ponderation )
          @endif
        @else
          @if(App\Objectif::getObjectif($e->id,$user->id, $sub->id))
            @php( $usersousTotal += App\Objectif::getObjectif($e->id,$user->id, $sub->id)->userNote * $sub->ponderation )
          @endif
          @if(App\Objectif::getObjectif($e->id,$user->id, $sub->id))
            @php( $mentorsousTotal += App\Objectif::getObjectif($e->id,$user->id, $sub->id)->mentorNote * $sub->ponderation )
          @endif
        @endif
        <tr>
          <td>
            {{ $sub->title }}
          </td>
          <td>
            {{ $sub->ponderation }}
          </td>
          <td>
            {{App\Objectif::getObjectif($e->id,$user->id, $sub->id) ? App\Objectif::getObjectif($e->id,$user->id, $sub->id)->mentorNote : '0' }}
          </td>
          <td>
            {{App\Objectif::getObjectif($e->id,$user->id, $sub->id) ? App\Objectif::getObjectif($e->id,$user->id, $sub->id)->mentorAppreciation : '---' }}
          </td>
        </tr>
      @endforeach
      @if (!empty($objectif->extra_fields))
        @foreach (json_decode($objectif->extra_fields) as $key => $field)
          <tr>
            <td>
              {{ $field->label }}
            </td>
            <td colspan="3">
              @if ($field->type == 'text')
                <span>{{ App\Objectif::getExtraFieldData($e->id, $user->id, $sub->id, $key, false) }}</span>
              @elseif ($field->type == 'textarea')
                <span>{{ App\Objectif::getExtraFieldData($e->id,$user->id, $sub->id, $key, false) }}</span>
              @endif
            </td>
          </tr>
        @endforeach
      @endif
      <tr class="sous-total">
        <td>Sous-total du Mentor (%)</td>
        <td colspan="3" class="sousTotal">
          <span class="badge badge-success pull-right">
            {{ $sumPonderation > 0 ? round($mentorsousTotal / $sumPonderation) : 0 }}
          </span>
        </td>
      </tr>
      @php( $userTotal += App\Objectif::cutNum($usersousTotal/$sumPonderation))
      @php( $mentorTotal += App\Objectif::cutNum($mentorsousTotal/$sumPonderation))
    @endforeach
    <tr class="total">
      <td>Total du Mentor (%)</td>
      <td colspan="3">
        <span class="badge badge-success pull-right">
          {{ $c > 0 ? round($mentorTotal / $c) : 0 }}
        </span>
      </td>
    </tr>
  </table>
@else
  @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
@endif

{{-- ****************** Commentaires ********************** --}}
<div class="mt-20"><p class="section-title">Emargement et commentaires</p></div>
<table class="table mt-20">
  <tbody>
    <tr>
      <td width="20%">Nom du manager :</td>
      <td>{{ $user->parent->last_name }}</td>
    </tr>
    <tr>
      <td width="20%">Commentaire du manager :</td>
      <td>{{ $comment->mentorComment or '---' }}</td>
    </tr>
    <tr>
      <td width="20%">Signature du manager :</td>
      <td>{{ $user->parent->name . ' ' .$user->parent->last_name }}</td>
    </tr>
    <tr>
      <td width="20%">Date de la revue :</td>
      <td>{{ $comment->mentor_updated_at != null ? Carbon\Carbon::parse($comment->mentor_updated_at)->format('d.m.Y') : '' }}</td>
    </tr>
  </tbody>
</table>
<table class="table mt-20">
  <tbody>
  <tr>
    <td width="20%">Nom du collaborateur :</td>
    <td>{{ $user->last_name }}</td>
  </tr>
  <tr>
    <td width="20%">Commentaire du collaborateur :</td>
    <td>{{ $comment->userComment or '---' }}</td>
  </tr>
  <tr>
    <td width="20%">Signature du collaborateur :</td>
    <td>{{ $user->name . ' ' .$user->last_name }}</td>
  </tr>
  <tr>
    <td width="20%">Date de la revue :</td>
    <td>{{ Carbon\Carbon::parse($comment->created_at)->format('d.m.Y')}}</td>
  </tr>
  </tbody>
</table>