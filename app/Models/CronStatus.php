<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class CronStatus extends Model
{
    //
    use Notifiable;
    protected $table = 'cron_job';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cron_status'
    ];
}
