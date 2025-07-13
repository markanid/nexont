<?php

namespace Modules\Member\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// use Modules\Purchase\Database\Factories\VendorFactory;

class Vendor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['company_name','company_phone', 'email', 'rep_name', 'rep_phone', 'address', 'logo', 'website', 'gst_number', 'status'];
}
