<?php

namespace Modules\Member\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Project\app\Models\Project;
use Modules\Projection\app\Models\Projection;
use Modules\Projection\app\Models\RunningProject;
use Modules\Timesheet\app\Models\Activity;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'employee';
    protected $fillable = ['employee_code', 'name', 'phone', 'email', 'password', 'designation', 'target', 'reporting_to', 'status', 'image'];

    protected $hidden = [
        'password',
    ];
    
    public static function getEmployeeCode()
    {
        $lastCode = Employee::orderBy('employee_code', 'desc')->first();
        if (!$lastCode) {
            return 'NX1001';
        }
        $lastCode = $lastCode->employee_code;
        $number     = (int) str_replace('NX', '', $lastCode);
        return 'NX' . str_pad($number + 1, 4, '0', STR_PAD_LEFT);
    }

    public function projections()
    {
        return $this->hasMany(Projection::class, 'created_by');
    }

    public function runningProjects()
    {
        return $this->hasMany(RunningProject::class, 'created_by', 'id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'employee_id' , 'id');
    }
    
    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'project_manager_id');
    }

    public function salesProjects()
    {
        return $this->hasMany(Project::class, 'sales_manager_id');
    }

    public function reportingTo()
    {
        return $this->belongsTo(Employee::class, 'reporting_to');
    }

    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'reporting_to');
    }
}
