<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class HeadLineImages extends Model
{
    //
    use Notifiable;

    protected $table = 'top_headlines_images';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'headline_id' , 'image_path'
    ];
}
