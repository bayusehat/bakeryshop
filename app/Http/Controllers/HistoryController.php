<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoryStok;
use Datatables;

class HistoryController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'History Stok',
            'content' => 'stok'
        ];

        return view('layout.index',['data'=>$data]);
    }

    public function loadData(Request $request)
    {
        if ($request->ajax()) {
            $data = HistoryStok::with('item.item_master.kategori');
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('nama_item', function($row){
                        return $row->item->item_master->nama_item;
                    })
                    ->addColumn('nama_kategori', function($row){
                        return $row->item->item_master->kategori->nama_kategori;
                    })
                    ->addColumn('mod_stok',function($row){
                        return number_format($row->mod_stok);
                    })
                    ->addColumn('action',function($row){
                        return $row->action;
                    })
                    ->addColumn('id_action',function($row){
                        return $row->id_action;
                    })
                    ->make(true);
        }
    }
}
