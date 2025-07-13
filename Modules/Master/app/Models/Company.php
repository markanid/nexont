<?php

namespace Modules\Master\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Project\app\Models\Project;

class Company extends Model
{
    protected $fillable = ['name', 'address', 'phone', 'email', 'website', 'type', 'client_id', 'logo_path'];
    use HasFactory;

    public function users()
    {
        return $this->hasMany(User::class, 'company_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'company_id');
    }
}
