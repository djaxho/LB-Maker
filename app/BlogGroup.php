<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogGroup extends Model implements ModelInterface
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'team_id',
        'name',
        'mailchimp_key'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($blogGroup) {
            $blogGroup->blogs->each->delete();
        });
    }

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

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'blog_group_id');
    }

    public function leadboxes()
    {
        return $this->hasManyThrough(Leadbox::class, Blog::class);
    }

    public function fillableAttributesForForms()
    {
        return array_diff($this->getFillable(), ['user_id', 'team_id']);
    }
}
