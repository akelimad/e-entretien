@extends('layouts.app')
@section('title', 'Fiches métiers')
@section('breadcrumb')
  <li>Fiches métiers</li>
@endsection
@section('style')
  @parent
  <link rel="stylesheet" href="{{ asset('vendor/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">
@endsection
@section('content')
  <section class="content p-sm-10" id="content">
    <div class="row">
      <div class="col-md-12">
        <div class="title-section mb-20">
          <h3 class="mt-0">
            <i class="fa fa-graduation-cap"></i> {{ __("Fiches métiers (REM/REC)") }} <span class="badge badge-count">0</span>
            <div class="pull-md-right pull-sm-right">
              <a
                  href="javascript:void(0)"
                  chm-modal="{{ route('skill.form') }}"
                  chm-modal-options='{"form":{"attributes":{"id":"skillForm","target-table":"[chm-table]"}}, "width": 700}'
                  class="btn bg-maroon"
              ><i class="fa fa-plus"></i>&nbsp;{{ "Ajouter" }}</a>
            </div>
          </h3>
        </div>
      </div>
      <div class="col-md-12">
        <div class="box p-0">
          <div class="box-body p-0">
            <div chm-table="{{ route('skills.table') }}"
                 chm-table-options='{"with_ajax": true}'
                 chm-table-params='{{ json_encode(request()->query->all()) }}'
                 id="SkillsTableContainer"
            ></div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('javascript')
  @parent
  <script src="{{asset('vendor/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
  <script>

    $(document).ready(function () {

    })

  </script>
@endsection