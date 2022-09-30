<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kategori extends Model
{
    use SoftDeletes;
    protected $table = 'kategoris';
    protected $primaryKey = 'id_kategori';
    protected $hidden = ['created_at', 'updated_at'];

    public $timestamps = true;

    public function item()
    {
        return $this->hasMany('App\Models\Item', 'id_kategori');
    }
}
