<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Http\Controllers\ItemController;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'content' => 'dashboard',
            'transaksi' => Transaksi::count(),
            'become_expired' => $this->becomeExpired('become'),
            'expired' => $this->becomeExpired('expired'),
            'item' => Item::count()
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
            if(substr($cek,0,14) == 'Become Expired'){
                $exitem[] = $i->id_item;
            }else if($cek == 'Expired'){
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
