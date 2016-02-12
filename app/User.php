<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Cache;
use DB;

class User extends Authenticatable
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'language_id', 'game_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function advanced_crafter_entries()
    {
        return $this->hasMany('App\Models\AdvancedCrafter');
    }

    public function valid_advanced_crafter_entries()
    {
        return $this->hasMany('App\Models\AdvancedCrafter')->where('valid_until', '>', DB::raw('now()'));
    }

    public function getAdvancedAttribute()
    {
        return $this->is_advanced_crafter();
    }

    public function is_advanced_crafter($force_refresh = false)
    {
        // Set the Cache ID
        $cid = 'ac_' + $this->id;

        // Force a data refresh?
        if ($force_refresh && Cache::has($cid))
            Cache::forget($cid);

        // Remember value for one week
        return Cache::remember($cid, 10080, function() {
            $ac = $this->advanced_crafter_entries()->orderBy('valid_until', 'DESC')->first();
            return is_null($ac) ? false : $ac->valid_until->getTimestamp();
        });
    }

}
