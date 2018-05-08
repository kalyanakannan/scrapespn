<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class HeadLines extends Model
{
    //
    use Notifiable;

    protected $table = 'top_headlines';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'story_id' , 'headline' , 'story_content'
    ];

    /**
     * Get the images for the story.
     */
    public function images()
    {
        return $this->hasMany('App\Models\HeadLineImages','headline_id');
    }
}
