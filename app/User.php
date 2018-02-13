<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'profession',
        'about',
        'password',
        'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * This will be used until the need arises to deal with
     * users who are part of many teams
     * @return mixed
     */
    public function team()
    {
        return $this->teams()->first();
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function joinTeam(Team $team)
    {
        $this->teams()->attach($team);
    }

    public function leaveTeam(Team $team)
    {
        $this->teams()->detach($team);
    }

    public function blogGroups()
    {
        return $this->hasMany(BlogGroup::class);
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

    public function leadboxes()
    {
        return $this->hasMany(Leadbox::class);
    }

    public function fillableAttributesForForms()
    {
        return array_diff($this->getFillable(), ['password', 'active']);
    }
}
