<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiDetail extends Model
{
    use SoftDeletes;
    protected $table = 'transaksi_details';
    protected $primaryKey = 'id_tranksasi_detail';
    protected $hidden = ['created_at', 'updated_at'];

    public $timestamps = true;

    public function transaksi()
    {
        return $this->belongsTo('App\Models\Transaksi','id_transaksi');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Item','id_item');
    }
}
