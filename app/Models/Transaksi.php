<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use SoftDeletes;
    protected $table = 'transaksis';
    protected $primaryKey = 'id_transaksi';
    protected $hidden = ['created_at', 'updated_at'];

    public $timestamps = true;

    public function detail()
    {
        return $this->hasMany('App\Models\TransaksiDetail','id_transaksi');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Auth','id_user');
    }
}
