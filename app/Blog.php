<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model implements ModelInterface
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'team_id',
        'blog_group_id',
        'name',
        'url',
        'main_text',
        'button_text',
        'mailchimp_list',
        'mailchimp_group'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($blog) {
            $blog->leadboxes->each->delete();
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

    public function blogGroup()
    {
        return $this->belongsTo(BlogGroup::class, 'blog_group_id');
    }

    public function leadboxes()
    {
        return $this->hasMany(Leadbox::class);
    }

    public function fillableAttributesForForms()
    {
        return array_diff($this->getFillable(), ['user_id', 'team_id', 'blog_group_id', 'created_at', 'updated_at']);
    }
}
