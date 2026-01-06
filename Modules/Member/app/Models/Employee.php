<?php

namespace Modules\Member\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Project\app\Models\Project;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['employee_code', 'name', 'phone', 'email', 'designation', 'status', 'image'];

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
}
