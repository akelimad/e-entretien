@extends('layouts.app')
@section('content')
    <section class="content skills">
        <div class="row">
            <div class="col-md-12">
                @if(Session::has('success_update'))
                    @include('partials.alerts.success', ['messages' => Session::get('success_update') ])
                @endif
                <div class="box box-primary card">
                    <h3 class="mb40"> La liste des compétences <span class="badge">{{$skills->total()}}</span> pour : {{$e->titre}}</h3>
                    <div class="nav-tabs-custom">
                        @include('partials.tabs')
                        <div class="tab-content">
                            @if(count($skills)>0)
                                <div class="box-body table-responsive no-padding mb40">
                                    <form action="{{ url('skills/updateUserSkills') }}">
                                        <input type="hidden" name="entretien_id" value="{{$e->id}}">
                                        <input type="hidden" name="user_id" value="{{$user->id}}">
                                        <input type="hidden" name="mentor_id" value="{{App\User::getMentor($user->id) ? App\User::getMentor($user->id)->id : 0}}">
                                        <table class="table table-hover table-bordered table-inversed-blue text-center">
                                            <tr>
                                                <th>Axe</th>
                                                <th>Famille</th>
                                                <th>Catégorie</th>
                                                <th>Compétence</th>
                                                <th>Objectif</th>
                                                <th>Auto</th>
                                                <th>N+1</th>
                                                <th>Ecart</th>
                                            </tr>
                                            @php($totalObjectif = 0)
                                            @php($totalAuto = 0)
                                            @php($totalNplus1 = 0)
                                            @php($totalEcart = 0)
                                            @foreach($skills as $skill)
                                            <tr>
                                                <td> {{ $skill->axe ? $skill->axe : '---' }}</td>
                                                <td> {{ $skill->famille ? $skill->famille : '---' }} </td>
                                                <td> {{ $skill->categorie ? $skill->categorie : '---' }} </td>
                                                <td> {{ $skill->competence ? $skill->competence : '---' }} </td>
                                                <td>
                                                    <input type="number" min="0" max="10" name="skills[{{$skill->id}}][objectif]" value="{{ App\Skill::getSkill($skill->id, $user->id, $e->id) ? App\Skill::getSkill($skill->id, $user->id, $e->id)->objectif : '' }}" id="objectif-{{ $skill->id }}" data-id="" {{$user->id == Auth::user()->id ? 'readonly':'' }}>
                                                    @php($totalObjectif += App\Skill::getSkill($skill->id, $user->id, $e->id) ? App\Skill::getSkill($skill->id, $user->id, $e->id)->objectif : 0)
                                                </td>
                                                <td>
                                                    <input type="number" min="0" max="10" name="skills[{{$skill->id}}][auto]" value="{{ App\Skill::getSkill($skill->id, $user->id, $e->id) ? App\Skill::getSkill($skill->id, $user->id, $e->id)->auto : '' }}" id="auto" data-id="{{ $skill->id }}" {{$user->id != Auth::user()->id ? 'readonly':'' }} >
                                                    @php($totalAuto += App\Skill::getSkill($skill->id, $user->id, $e->id) ? App\Skill::getSkill($skill->id, $user->id, $e->id)->auto : 0)
                                                </td>
                                                <td>
                                                    <input type="number" min="0" max="10" name="skills[{{$skill->id}}][nplus1]" value="{{ App\Skill::getSkill($skill->id, $user->id, $e->id) ? App\Skill::getSkill($skill->id, $user->id, $e->id)->nplus1 : '' }}" class="nplus1" data-id="{{ $skill->id }}" {{$user->id == Auth::user()->id ? 'readonly':'' }}>
                                                    @php($totalNplus1 += App\Skill::getSkill($skill->id, $user->id, $e->id) ? App\Skill::getSkill($skill->id, $user->id, $e->id)->nplus1 : 0)
                                                </td>
                                                <td>
                                                    <input type="number" name="skills[{{$skill->id}}][ecart]" value="{{ App\Skill::getSkill($skill->id, $user->id, $e->id) ? App\Skill::getSkill($skill->id, $user->id, $e->id)->ecart : '' }}" id="ecart-{{ $skill->id }}" data-id="" {{$user->id == Auth::user()->id ? 'readonly':'' }}>
                                                    @php($totalEcart += App\Skill::getSkill($skill->id, $user->id, $e->id) ? App\Skill::getSkill($skill->id, $user->id, $e->id)->ecart : 0)
                                                </td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="4">
                                                    Totaux des compétences :
                                                </td>
                                                <td><span class="badge">{{$totalObjectif}}</span></td>
                                                <td><span class="badge">{{$totalAuto}}</span></td>
                                                <td><span class="badge">{{$totalNplus1}}</span></td>
                                                <td><span class="badge">{{$totalEcart}}</span></td>
                                            </tr>
                                        </table>
                                        @if(!App\Skill::filledSkills($e->id, $user->id, $user->parent->id))
                                        <button type="submit" class="btn btn-success pull-right" > <i class="fa fa-check"></i> Sauvegarder</button>
                                        @endif
                                        {{ $user->id }} parent:{{ $user->parent->id }}
                                    </form>
                                    {{ $skills->links() }}
                                </div>
                            @else
                                @include('partials.alerts.info', ['messages' => "Aucune donnée trouvée dans la table ... !!" ])
                            @endif
                        </div>
                    </div>
                    <div class="callout callout-info">
                        <p class="">
                            <i class="fa fa-info-circle fa-2x"></i> 
                            <span class="content-callout">Cette page affiche la liste des compétences de la part du collaborateur: <b>{{ $user->name." ".$user->last_name }}</b> pour l'entretien: <b>{{ $e->titre }}</b> </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
  

