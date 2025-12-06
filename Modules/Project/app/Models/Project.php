<?php

namespace Modules\Project\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\app\Models\Company;
use Modules\Master\app\Models\User;


class Project extends Model
{
    use HasFactory;
    
    protected $fillable = ['project_id', 'project_name', 'company_id', 'client_id', 'project_manager_id', 'sales_manager_id', 'start_date', 'project_cid', 'po', 'apr_main_steel', 'apr_misc_steel', 'po_main_sd', 'po_misc_sd', 'po_engineering', 'po_currency', 'kitty', 'covalue', 'status'];

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

    public static function getProjectID()
    {
        $lastCode = Project::latest('id')->first();
        if (!$lastCode) {
            return 'PRJ_001';
        }
        $lastCode = $lastCode->project_id;
        $codeParts = explode('_', $lastCode);
        $number = intval(end($codeParts)) + 1;
        return 'PRJ_' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
    
}
