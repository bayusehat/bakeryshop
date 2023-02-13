<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemMaster extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'item_masters';
    protected $primaryKey = 'id_item_master';
    protected $hidden = ['created_at','updated_at','deleted_at'];
    // p

    public $timestamps = true;

    public function kategori()
    {
        return $this->hasOne('App\Models\Kategori','id_kategori','id_kategori');
    }

    public function item()
    {
        return $this->hasOne('App\Models\Item','id_item_master','id_item_master');
    }
}
