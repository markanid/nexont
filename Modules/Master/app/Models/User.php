<?php

namespace Modules\Master\app\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Project\app\Models\Project;

class User extends Authenticatable
{
    function __construct(){
        //
    }
    protected $fillable = ['name', 'email', 'password', 'avatar', 'company_id'];
    protected $hidden = ['password'];
    use HasFactory;

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function clientProjects()
    {
        return $this->hasMany(Project::class, 'client_id');
    }
}
