<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model implements ModelInterface
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'label',
        'about'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function addUser(User $user)
    {
        $this->users()->attach($user);
    }

    public function removeUser(User $user)
    {
        $this->users()->detach($user);
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
        return array_diff($this->getFillable(), []);
    }
}
