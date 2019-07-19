
<div class="content subQuestions">
    <input type="hidden" name="id" value="{{ isset($q) ? $q->id : null }}">
    <input type="hidden" name="survey_id" value="{{ $sid }}">
    <input type="hidden" name="groupe_id" value="{{ $gid }}">
    <input type="hidden" name="parent_id" value="{{ isset($parent_id) ? $parent_id : '' }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="titre" class="col-md-2 control-label">Titre</label>
        <div class="col-md-10">
            <textarea name="titre" id="titre" class="form-control">{{isset($q) ? $q->titre :''}}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="type" class="col-md-2 control-label">Type</label>
        <div class="col-md-10">
            <select name="type" id="questionType" class="form-control">
                <option value="text" {{ isset($q) && $q->type == "text" ? 'selected':''  }} >Text</option>
                <option value="textarea" {{ isset($q) && $q->type == "textarea" ? 'selected':''  }}>Textarea</option>
                <option value="slider" {{ isset($q) && $q->type == "slider" ? 'selected':''  }}>Slider note</option>
                <option value="checkbox" {{ isset($q) && $q->type == "checkbox" ? 'selected':''  }}>Case à cocher</option>
                <option value="radio" {{ isset($q) && $q->type == "radio" ? 'selected':''  }}>Radio button</option>
                <option value="rate" {{ isset($q) && $q->type == "rate" ? 'selected':''  }}>Rating</option>
            </select>
        </div>
    </div>
    <div id="addLine-wrap">
        @if( isset($q) && count($q->children)>0 )
            @foreach($q->children as $key => $choice)
            <div class="form-group" >
                <label class="col-md-2 control-label">choix : <span class="badge"> </span></label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="subQuestions[{{$choice->id}}]" required="required" value="{{$choice->titre}}" />
                </div>
                <div class="col-md-8 col-md-offset-2">
                    <input type="text" class="form-control" name="subQuestionsOptions[{{$choice->id}}][label]" required="required" value="{{ json_decode($choice->options)->label }}" />
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-info {{ $key == 0 ? 'addLine':'deleteLine'}}"><i class="fa {{ $key == 0 ? 'fa-plus':'fa-minus' }}"></i></button>
                </div>
            </div>
            @endforeach
        @else
            <div class="form-group" >
                <label class="col-md-2 control-label">choix : <span class="badge"> </span></label>
                <div class="col-md-8">
                    <input type="text" id="choiceField" class="form-control" name="subQuestions[0]" required="required" value="" />
                </div>
                <div class="col-md-8 col-md-offset-2">
                    <input type="text" class="form-control" name="subQuestionsLabel[0]" required="required" />
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-info addLine"><i class="fa fa-plus"></i></button>
                </div>
            </div>
        @endif
    </div>

</div>

<script>
jQuery(document).ready(function() {
    $('body').on('change', '#questionType', function () {
        showHideQuestionChoices()
    })
    $('#questionType').trigger('change')
    function showHideQuestionChoices() {
        var value = $('#questionType').val()
        if ($.inArray(value, ['radio','checkbox', 'select', 'rate']) !== -1) {
            $('#addLine-wrap').show()
            $('#addLine-wrap #choiceField').prop('required', true).attr('name', 'subQuestions[0]')
        } else {
            $('#addLine-wrap').hide()
            $('#addLine-wrap #choiceField').prop('required', false).attr('name', '')
        }
    }
})
</script>