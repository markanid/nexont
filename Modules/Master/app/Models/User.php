<?php

namespace Modules\Master\app\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Consumption\app\Models\Consumption;
use Modules\Estimation\app\Models\Estimation;
use Modules\Finance\app\Models\Expense;
use Modules\Project\app\Models\Project;
use Modules\Projection\app\Models\Projection;
use Modules\Projection\app\Models\RunningProject;
use Modules\Purchase\app\Models\Purchase;
use Modules\Returns\app\Models\Returns;
use Modules\Sale\app\Models\Sale;
use Modules\Service\app\Models\Service;

class User extends Model
{
    function __construct(){
        //
    }
    protected $fillable = ['name', 'email', 'password', 'avatar', 'role', 'company_id'];
    
    use HasFactory;

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function clientProjects()
    {
        return $this->hasMany(Project::class, 'client_id');
    }
    
    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'project_manager_id');
    }

    public function salesProjects()
    {
        return $this->hasMany(Project::class, 'sales_manager_id');
    }

    public function projections()
    {
        return $this->hasMany(Projection::class, 'created_by');
    }

    public function runningProjects()
    {
        return $this->hasMany(RunningProject::class, 'created_by', 'id');
    }
}
