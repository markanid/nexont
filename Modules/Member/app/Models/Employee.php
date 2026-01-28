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
    protected $fillable = ['employee_code', 'name', 'phone', 'email', 'password', 'designation', 'status', 'image'];

    protected $hidden = [
        'password',
    ];
    
    public static function getEmployeeCode()
    {
        $lastCode = Employee::latest('id')->first();
        if (!$lastCode) {
            return 'EMP_001';
        }
        $lastCode = $lastCode->employee_code;
        $codeParts = explode('_', $lastCode);
        $number = intval(end($codeParts)) + 1;
        return 'EMP_' . str_pad($number, 3, '0', STR_PAD_LEFT);
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
}
