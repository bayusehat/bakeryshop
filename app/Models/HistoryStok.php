<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistoryStok extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'history_stoks';
    protected $primaryKey = 'id_history_stok';
    protected $hidden = ['created_at','updated_at'];

    public $timestamps = true;

    /**
     * Get the item that owns the HistoryStok
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'id_item', 'id_item');
    }
}
