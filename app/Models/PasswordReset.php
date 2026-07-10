<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $guarded = [];

    use HasFactory, SoftDeletes;

    protected $table = 'password_reset';
    protected $fillable = [
        'email_id', 'token'
    ];
}