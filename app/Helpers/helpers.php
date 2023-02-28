<?php
use App\Models\Item;
use App\Models\HistoryStok;

if (! function_exists('history_stok')) {
    function history_stok($id_item,$mod_stok,$action,$id_action)
    {
        $history = new HistoryStok;
        $history->id_item = $id_item;
        $history->mod_stok = $mod_stok;
        $history->action = $action;
        $history->id_action = $id_action;
        $history->save();
    }
} 
?>