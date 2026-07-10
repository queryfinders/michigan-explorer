<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    protected $guarded = [];

    use HasFactory, SoftDeletes;

    protected $table = 'admin_user';
    protected $fillable = [
        'name', 'password', 'email_id', 'contact_no', 'job_title', 'profile_url', 'role_id', 'is_admin','created_by', 'updated_by', 'deleted_by'
    ];
    protected $hidden = [
        'password'
    ];
}