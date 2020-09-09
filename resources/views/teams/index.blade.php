@extends('layouts.app')
@section('title', 'Equipes')
@section('breadcrumb')
  <li>Equipes</li>
@endsection
@section('content')
  <section class="content users">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header">
            <h3 class="box-title">Liste des équipes <span class="badge badge-count"></span></h3>
            <div class="box-tools mb40">
              <a
                  href="javascript:void(0)"
                  chm-modal="{{ route('team.form') }}"
                  chm-modal-options='{"form":{"attributes":{"id":"teamForm","target-table":"[chm-table]"}}}'
                  class="btn bg-maroon"
              ><i class="fa fa-plus"></i>&nbsp;{{ "Ajouter" }}</a>
            </div>
          </div>
          <div class="box-body">
            <div chm-table="{{ route('teams.table') }}"
                 chm-table-options='{"with_ajax": true}'
                 chm-table-params='{{ json_encode(request()->query->all()) }}'
                 id="TeamsTableContainer"
            ></div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection