<style>
  .slider.slider-horizontal {
    width: 100% !important;
  }
  .margin-bottom {
    border-bottom: 1px solid #d9edf7;
    padding-bottom: 10px;
    margin-bottom: 10px;
  }
  .pointer {
    cursor: pointer;
  }
  .array-table tbody tr td label.table-radio-item {
    cursor: pointer;
    display: block;
    margin: 0;
    height: 100%;
  }
  .array-qst-note {
    background: #e6d3b0 !important;
  }

</style>
<div class="row">
  <div class="col-md-12 survey">
    @if(count($groupes)>0)
      <form action="{{url('answers/store')}}" method="post" class="surveyForm">
        <input type="hidden" name="entretien_id" value="{{$e->id}}">
        <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
        <input type="hidden" name="mentor_id" value="{{ $e->isFeedback360() ? $evaluator_id : Auth::user()->parent->id }}">
        {{ csrf_field() }}
        <div class="panel-group">
          @foreach($groupes as $g)
            @if(count($g->questions)>0)
              <div class="panel panel-info mb-20">
                <div class="panel-heading">{{ $g->name }}</div>
                <div class="panel-body">
                  @forelse($g->questions as $keyQst => $q)
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          @if(in_array($q->type, ['text', 'textarea', 'radio']))
                            <input type="text" data-group-target="{{$g->id}}" name="answers[{{$q->id}}][note]" class="notation" min="1" max="{{App\Setting::get('max_note')}}" style="display:none;">
                          @endif
                          @if($q->parent == null)
                            <label for="" class="questionTitle">{{$q->titre}}</label>
                          @endif
                          @if($q->type == 'text')
                            <input type="{{$q->type}}" name="answers[{{$q->id}}][ansr]" class="form-control" value="{{App\Answer::getCollAnswers($q->id, $user->id, $e->id) ? App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer : '' }}" required {{ !App\Entretien::answered($e->id, Auth::user()->id) ? '':'readonly' }}>
                          @elseif($q->type == 'textarea')
                            <textarea name="answers[{{$q->id}}][ansr]" class="form-control" required {{ !App\Entretien::answered($e->id, Auth::user()->id) ? '':'readonly' }}>{{App\Answer::getCollAnswers($q->id, $user->id, $e->id) ? App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer : '' }}</textarea>
                          @elseif($q->type == "checkbox")
                            <input type="text" data-group-target="{{$g->id}}" name="answers[{{$q->id}}][note]"  class="notation" min="1" max="{{App\Setting::get('max_note')}}" @if($g->notation_type == 'section')style="display:none;"@endif>
                            <p class="help-inline text-red checkboxError"><i class="fa fa-close"></i> Veuillez cocher au moins un élement</p>
                            @foreach($q->children as $child)
                              <div class="">
                                <input type="{{$q->type}}" name="answers[{{$q->id}}][ansr][]" id="{{$child->titre}}" value="{{$child->id}}" {{ App\Answer::getCollAnswers($q->id, $user->id, $e->id) && in_array($child->id, json_decode(App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer)) ? 'checked' : '' }}>
                                <label for="{{$child->titre}}">{{ $child->titre }}</label>
                              </div>
                            @endforeach
                            <div class="clearfix"></div>
                          @elseif($q->type == "radio")
                            @foreach($q->children as $child)
                              <div class="choice-item">
                                <input type="{{$q->type}}" name="answers[{{$q->id}}][ansr]" id="{{$child->id}}" value="{{$child->id}}" required {{ App\Answer::getCollAnswers($q->id, $user->id, $e->id) && App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer == $child->id ? 'checked' : '' }}>
                                <label for="{{$child->id}}" class="d-inline-block">{{ $child->titre }}</label>
                              </div>
                            @endforeach
                          @elseif($q->type == "slider")
                            <div class="" style="margin-top: 30px;">
                              <input type="text" required="" name="answers[{{$q->id}}][ansr]" data-provide="slider"
                                     data-slider-min="1"
                                     data-slider-max="5"
                                     data-slider-step="1"
                                     data-slider-value="{{App\Answer::getCollAnswers($q->id, $user->id, $e->id) ? App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer : '' }}"
                                     data-slider-tooltip="always"
                                     data-slider-ticks="[1, 2, 3, 4, 5]"
                                     data-slider-ticks-labels='["1", "2", "3", "4", "5"]'
                              >
                            </div>
                          @elseif ($q->type == "rate")
                            @foreach($q->children as $child)
                              <div class="row margin-bottom">
                                <div class="col-md-1">
                                  <input type="radio" name="answers[{{$q->id}}][ansr]" value="{{ $child->id }}" id="user-{{ $child->id }}" {{ App\Answer::getCollAnswers($q->id, $user->id, $e->id) && App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer == $child->id ? 'checked' : '' }}> {{ $child->titre }}
                                </div>
                                <div class="col-md-11">
                                  @php($options = json_decode($child->options, true))
                                  <label class="pull-right pointer" for="user-{{ $child->id }}">
                                    {{ isset($options['label']) ? $options['label'] : 'vide' }}
                                  </label>
                                </div>
                              </div>
                            @endforeach
                          @elseif($q->type == "select")
                            <div class="row">
                              <div class="col-md-3">
                                <select name="answers[{{$q->id}}][ansr]" id="" class="form-control" required>
                                  <option value=""></option>
                                  @foreach($q->children as $child)
                                    <option value="{{ $child->id }}" {{ App\Answer::getCollAnswers($q->id, $user->id, $e->id) && App\Answer::getCollAnswers($q->id, $user->id, $e->id)->answer == $child->id ? 'selected' : '' }}>{{ $child->titre }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          @elseif($q->type == "array")
                              @php($qAnswer = App\Answer::getCollAnswers($q->id, $user->id, $e->id))
                              @php($qAnswer = $qAnswer ? json_decode($qAnswer->answer, true) : [])
                              <table class="table table-hover">
                                <thead>
                                <tr>
                                  <th></th>
                                  @foreach($q->getOptions('answers') as $ansrKey => $answer)
                                    <th class="text-center">{{ $answer['title'] }}</th>
                                  @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($q->getOptions('subquestions') as $subKey => $subquestion)
                                  <tr>
                                    <td>{{ $subquestion['title'] }}</td>
                                    @foreach($q->getOptions('answers') as $key => $answer)
                                      <td class="text-center">
                                        <input type="radio" name="answers[{{$q->id}}][ansr][{{ $subquestion['id'] }}]" value="{{ $answer['id'] }}" {{ isset($qAnswer[$subquestion['id']]) && $qAnswer[$subquestion['id']] == $answer['id'] ? 'checked':'' }} required>
                                      </td>
                                    @endforeach
                                  </tr>
                                @endforeach
                                </tbody>
                              </table>
                          @endif
                        </div>
                      </div>
                    </div>
                  @empty
                    <p class="help-block">Aucune question</p>
                  @endforelse
                </div>
              </div>
            @endif
          @endforeach
        </div>
        <div class="actions p-20 bg-gray clearfix">
          @if(!App\Entretien::answered($e->id, Auth::user()->id))
            <button type="submit" class="btn btn-success pull-md-right pull-sm-right btn-xs-block mb-sm-10" id="submitAnswers"><i class="fa fa-save"></i> {{ __("Enregistrer") }}</button>
          @endif
        </div>
      </form>
    @else
      <p class="alert alert-default">{{ __("Aucune donnée disponible !") }}</p>
    @endif
  </div>
</div>
