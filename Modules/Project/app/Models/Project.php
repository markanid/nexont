<?php

namespace Modules\Project\app\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\app\Models\Company;
use Modules\Master\app\Models\User;
use Modules\Projection\app\Models\RunningProject;

class Project extends Model
{
    use HasFactory;
    
    protected $fillable = ['project_code', 'project_name', 'company_id', 'client_id', 'project_manager_id', 'sales_manager_id', 'start_date', 'project_cid', 'po', 'apr_main_steel', 'apr_misc_steel', 'po_main_sd', 'po_misc_sd', 'po_engineering', 'po_currency', 'kitty', 'covalue', 'status'];

    protected $casts = ['start_date' => 'date', 'po_main_sd'  => 'float', 'po_misc_sd'  => 'float', 'po_engineering'  => 'float', 'kitty'  => 'float', 'covalue'  => 'float'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function projectManager()
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function salesManager()
    {
        return $this->belongsTo(User::class, 'sales_manager_id');
    }

    public function runningProjects()
    {
        return $this->hasMany(RunningProject::class, 'project_id' , 'id');
    }

    public static function getProjectID()
    {
        $prefix = 'NX';
        $year   = Carbon::now()->format('y'); // 26

        $lastProject = Project::where('project_code', 'like', $prefix.$year.'%')
        ->latest('id')
        ->first();

        if (!$lastProject) {
            $series = 1;
        } else {
            // Extract last 3 digits (series)
            $lastSeries = (int) substr($lastProject->project_code, -3);
            $series = $lastSeries + 1;
        }

        return $prefix . $year . str_pad($series, 3, '0', STR_PAD_LEFT);
    }
    
}
