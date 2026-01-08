<?php

namespace Modules\Projection\app\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\app\Models\User;
use Modules\Project\app\Models\Project;

// use Modules\Projection\Database\Factories\ProjectionDetailFactory;

class RunningProject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['projection_id', 'project_id', 'projection_value', 'type', 'billing_desc', 'status', 'invoice_details', 'remarks', 'created_by'];

    public function projection()
    {
        return $this->belongsTo(Projection::class, 'projection_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
