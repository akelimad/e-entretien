{{ csrf_field() }}
<div class="row">
  <div class="col-md-12">
    <p>Réouvrir pour :</p>
  </div>
  <div class="col-md-12">
    <input type="hidden" name="ids" value="{{ json_encode($ids) }}">
    <div class="form-check mb-15">
      <input type="checkbox" id="user_submitted" name="fields[]" value="user" checked> <label for="user_submitted" class="font-14 mb-0"><b>Collaborateur</b></label>
    </div>
    <div class="form-check mb-15">
      <input type="checkbox" id="mentor_submitted" name="fields[]" value="mentor" checked> <label for="mentor_submitted" class="font-14 mb-0"><b>Manager</b></label>
    </div>
  </div>
</div>