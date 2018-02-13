<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leadbox extends Model implements ModelInterface
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'blog_id',
        'team_id',
        'name',
        'url',
        'main_text',
        'button_text'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWhereInTeam($query, $teamId) {
        return $query->where('team_id', $teamId);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function fillableAttributesForForms()
    {
        return array_diff($this->getFillable(), ['user_id', 'blog_id', 'team_id']);
    }
}
