<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use EntrustUserTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getMentor($uid)
    {
        $user = User::findOrFail($uid);
        if($user){
            return $user->parent;
        }else{
            return $user;
        }
    }

    public function parent()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function children()
    {
        return $this->hasMany('App\User', 'user_id');
    }

    /**
     * Get the interview for the given user.
     */
    public function entretiens()
    {
        return $this->belongsToMany('App\Entretien', 'entretien_user', 'user_id', 'entretien_id');
    }

    /**
     * Get the interview for the given user.
     */
    public function objectifs()
    {
        return $this->belongsToMany('App\Objectif');
    }

    /**
     * Get the interview for the given user.
     */
    public function skills()
    {
        return $this->belongsToMany('App\Skill');
    }

    

    /**
     * Get the interview for the given user.
     */
    public function activites()
    {
        return $this->hasMany('App\Activite');
    }

    public function answers()
    {
        return $this->hasMany('App\Answer');
    }

    public static function hasMotif($eid, $uid){
        $hasMotif = Entretien_user::where('entretien_id', $eid)->where('user_id', $uid)->first();
        if( $hasMotif->motif ){
            return $hasMotif->motif;
        }
        return null;
    }

}
