<?php

namespace Modules\Timesheet\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Member\app\Models\Employee;
use Modules\Project\app\Models\Project;

// use Modules\Timesheet\Database\Factories\ActivityFactory;

class Activity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['date', 'project_id', 'employee_id', 'approved_by', 'approved_at'];
    protected $casts = ['date' => 'date', 'approved_at' => 'datetime'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function activityCustoms()
    {
        return $this->hasMany(ActivityCustom::class, 'activity_id');
    }

    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    const STATUS_PENDING  = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;

    public function getApprovalStatusAttribute()
    {
        return match ($this->is_approved) {
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            default => 'Not Approved',
        };
    }

    public function getApprovalBadgeClassAttribute()
    {
        return match ($this->is_approved) {
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
            default => 'warning',
        };
    }

}
