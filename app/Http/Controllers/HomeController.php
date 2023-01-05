<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Http\Controllers\ItemController;
use DB;

class HomeController extends Controller
{
    public function index()
    {
        $chart = Transaksi::select(DB::raw("COUNT(*) as count"), DB::raw("MONTHNAME(created_at) as month_name"))
        ->whereYear('created_at', date('Y'))
        ->groupBy(DB::raw("month_name"))
        ->orderBy('created_at','ASC')
        ->pluck('count', 'month_name');

        $labels = $chart->keys();
        $data = $chart->values();

        $data = [
            'title' => 'Dashboard',
            'content' => 'dashboard',
            'transaksi' => Transaksi::count(),
            'become_expired' => $this->becomeExpired('become'),
            'expired' => $this->becomeExpired('expired'),
            'item' => Item::count(),
            'labels' => $labels,
            'data' => $data
        ];

        return view('layout.index',['data' => $data]);
    }

    public function becomeExpired($status)
    {
        $item = Item::get();
        $check = new ItemController;
        $exitem = [];
        $expired = [];
        foreach ($item as $i) {
            $cek = $check->checkExpired($i->expired_item);
            if(substr($cek,0,45) == '<span class="badge bg-warning">Become Expired'){
                $exitem[] = $i->id_item;
            }else if($cek == '<span class="badge bg-danger">Expired</span>'){
                $expired[] = $i->id_item;
            }
        }
        if($status == 'become'){
            return count($exitem);
        }else{
            return count($expired);
        }
    }
}
