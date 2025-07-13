<?php

namespace Modules\Member\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['employee_code', 'name', 'gender', 'date_of_birth', 'phone', 'email', 'address', 'designation', 'joining_date', 'status', 'image'];

    protected $dates = ['date_of_birth', 'joining_date'];

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
