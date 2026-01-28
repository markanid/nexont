<?php

namespace Modules\Timesheet\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Task\app\Models\Task;

// use Modules\Timesheet\Database\Factories\ActivityCustomFactory;

class ActivityCustom extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['activity_id', 'task_id', 'time_hours'];
    protected $casts = ['time_hours' => 'float'];

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
}
