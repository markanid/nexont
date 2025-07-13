<?php

namespace Modules\Master\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    protected $fillable = ['name', 'address', 'phone', 'email', 'website', 'type', 'client_id', 'logo_path'];
    use HasFactory;

    public function users()
    {
        return $this->hasMany(Company::class, 'company_id');
    }
}
