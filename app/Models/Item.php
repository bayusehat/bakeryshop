<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;
    protected $table = 'items';
    protected $primaryKey = 'id_item';
    protected $hidden = ['created_at', 'updated_at'];

    public $timestamps = true;

    public function kategori(){
        return $this->belongsTo('App\Models\Kategori', 'id_kategori');
    }
}
