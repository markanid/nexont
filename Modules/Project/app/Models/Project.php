<?php

namespace Modules\Project\app\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\app\Models\Company;
use Modules\Master\app\Models\User;
use Modules\Member\app\Models\Employee;
use Modules\Projection\app\Models\RunningProject;
use Modules\Timesheet\app\Models\Activity;

class Project extends Model
{
    use HasFactory;
    
    protected $fillable = ['project_code', 'project_name', 'company_id', 'client_id', 'project_manager_id', 'sales_manager_id', 'start_date', 'project_cid', 'po', 'apr_main_steel', 'apr_misc_steel', 'po_main_sd', 'po_misc_sd', 'po_engineering', 'po_currency', 'kitty', 'covalue', 'estimated_hours', 'status', 'app_misc_modeling', 'app_misc_detailing', 'app_misc_erection', 'app_misc_check_model', 'app_misc_check_det_erec', 'app_misc_total', 'app_misc_remarks', 'app_main_modeling', 'app_main_detailing', 'app_main_erection', 'app_main_check_model', 'app_main_check_det_erec', 'app_main_total', 'app_main_remarks', 'fab_misc_modeling', 'fab_misc_detailing', 'fab_misc_erection', 'fab_misc_check_model', 'fab_misc_check_det_erec', 'fab_misc_total', 'fab_misc_remarks', 'fab_main_modeling', 'fab_main_detailing', 'fab_main_erection', 'fab_main_check_model', 'fab_main_check_det_erec', 'fab_main_total', 'fab_main_remarks'];

    protected $casts = ['start_date' => 'date', 'po_main_sd'  => 'float', 'po_misc_sd'  => 'float', 'po_engineering'  => 'float', 'kitty'  => 'float', 'covalue'  => 'float', 'estimated_hours' => 'float', 'app_misc_modeling' => 'float', 'app_misc_detailing' => 'float', 'app_misc_erection' => 'float', 'app_misc_check_model' => 'float', 'app_misc_check_det_erec' => 'float', 'app_misc_total' => 'float', 'app_main_modeling' => 'float', 'app_main_detailing' => 'float', 'app_main_erection' => 'float',	'app_main_check_model' =>	'float','app_main_check_det_erec'=>	'float','app_main_total'=>	'float','fab_misc_modeling'=>	'float','fab_misc_detailing'=>	'float','fab_misc_erection'=>	'float','fab_misc_check_model'=>	'float','fab_misc_check_det_erec'=>	'float','fab_misc_total'=>	'float','fab_main_modeling'=>	'float','fab_main_detailing'=>	'float','fab_main_erection'=>	'float','fab_main_check_model'=>	'float','fab_main_check_det_erec'=>	'float','fab_main_total'=>	'float'];

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
        return $this->belongsTo(Employee::class, 'project_manager_id');
    }

    public function salesManager()
    {
        return $this->belongsTo(Employee::class, 'sales_manager_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'project_id' , 'id');
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
