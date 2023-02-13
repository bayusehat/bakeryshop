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
    protected $hidden = ['updated_at'];

    public $timestamps = true;

    public function item_master(){
        return $this->hasOne('App\Models\ItemMaster', 'id_item_master');
    }
}
