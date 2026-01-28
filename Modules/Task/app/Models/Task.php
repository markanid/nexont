<?php

namespace Modules\Task\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Timesheet\app\Models\ActivityCustom;

// use Modules\Task\Database\Factories\TaskFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'category_code', 'activity_code', 'work_descipline', 'production_type', 'production_stage', 'estimated_hour'];

    public function activityCustoms()
    {
        return $this->hasMany(ActivityCustom::class, 'activity_id');
    }
}
