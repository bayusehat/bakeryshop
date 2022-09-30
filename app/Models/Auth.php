<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Auth extends Model
{
    use SoftDeletes;
    protected $table = 'auths';
    protected $primaryKey = 'id_user';
    protected $hidden = ['created_at', 'updated_at'];

    public $timestamps = true;
}
