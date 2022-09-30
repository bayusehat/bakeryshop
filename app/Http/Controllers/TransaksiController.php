<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Datatables;

class TransaksiController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Transaksi',
            'content' => 'transaksi',
            'item' => Item::with('kategori')->get()
        ];

        return view('layout.index',['data' => $data]);
    }

    public function addTransaksi(Request $request)
    {
        $trans = new Transaksi;
        $trans->nomor_transaksi = $this->generateBarcodeNumber();
        $trans->tgl_transaksi = date('Y-m-d H:i:s');
        $trans->id_user = session('id_user');
        $trans->total = $request->input('total');
        if($trans->save()){
            if($request->has('id_item')){
                foreach ($request->input('id_item') as $i => $td) {
                    $transDetail = new TransaksiDetail;
                    $transDetail->id_item = $td;
                    $transDetail->id_transaksi = $trans->id_transaksi;
                    $transDetail->qty = $request->input('qty')[$i];
                    $transDetail->subtotal = $request->input('subtotal')[$i];
                    $transDetail->save();
                }
                $checkTrans = TransaksiDetail::where('id_transaksi',$trans->id_transaksi)->first();
                if($checkTrans){
                    $this->calculateStok($trans->id_transaksi,1);
                    return redirect()->back()->with('success','Berhasil menambah transaksi!');
                }else{
                    return redirect()->back()->with('error','Gagal menambah transaksi!');
                }
            }else{
                return redirect()->back()->with('error','Detail transaksi tidak boleh kosong!');
            }
        }else{
            return redirect()->back()->with('error','Terjadi kesalahan menyimpan transaksi! coba lagi.');
        }
    }

    public function generateBarcodeNumber() {
        $number = mt_rand(1000000000, 9999999999); // better than rand()

        // call the same function if the barcode exists already
        // if (barcodeNumberExists($number)) {
        //     return generateBarcodeNumber();
        // }

        // otherwise, it's valid and can be used
        return $number;
    }

    public function loadData(Request $request)
    {
        // $whereLike = [
        //     'nomor_transaksi',
        //     'tgl_transaksi'
        // ];

        // $start  = $request->input('start');
        // $length = $request->input('length');
        // $order  = $whereLike[$request->input('order.0.column')];
        // $dir    = $request->input('order.0.dir');
        // $search = $request->input('search.value');

        // $totalData = Transaksi::with('user')->count();
        // if (empty($search)) {
        //     $queryData = Transaksi::with('user')
        //         ->offset($start)
        //         ->limit($length)
        //         ->orderBy($order, $dir)
        //         ->get();
        //     $totalFiltered = Transaksi::with('user')->count();
        // } else {
        //     $queryData =Transaksi::with('user')
        //         ->where(function($query) use ($search) {
        //             $query->where('nomor_transaksi', 'like', "%{$search}%");
        //             $query->orWhere('tgl_transaksi','like',"%{$search}%");
        //             $query->orWhere('username','like',"%{$search}%");
        //         })
        //         ->offset($start)
        //         ->limit($length)
        //         ->orderBy($order, $dir)
        //         ->get();
        //     $totalFiltered = Transaksi::with('user')
        //         ->offset($start)
        //         ->where(function($query) use ($search) {
        //             $query->where('nomor_transaksi', 'like', "%{$search}%");
        //             $query->orWhere('tgl_transaksi','like',"%{$search}%");
        //             $query->orWhere('username','like',"%{$search}%");
        //         })
        //         ->count();
        // }

        // $response['data'] = [];
        // if($queryData <> FALSE) {
        //     $nomor = $start + 1;
        //     foreach ($queryData as $val) {
        //             $response['data'][] = [
        //                 $nomor,
        //                 $val->nomor_transaksi,
        //                 date('d/m/Y H:i',strtotime($val->tgl_transaksi)),
        //                 $val->user->username,
        //                 number_format($val->total),
        //                 '
        //                 <a href="javascript:void(0)" class="btn btn-warning"><i class="fas fa-file"></i></a>
        //                 <a href="'.url('transaksi/edit/'.$val->id_transaksi).'" class="btn btn-primary"><i class="fas fa-edit"></i></a>
        //                 <a href="javascript:void(0)" class="btn btn-danger" onclick="deleteTransaksi('.$val->id_transaksi.')"><i class="fas fa-trash"></i></a>
        //                 '
        //             ];
        //         $nomor++;
        //     }
        // }

        // $response['recordsTotal'] = 0;
        // if ($totalData <> FALSE) {
        //     $response['recordsTotal'] = $totalData;
        // }

        // $response['recordsFiltered'] = 0;
        // if ($totalFiltered <> FALSE) {
        //     $response['recordsFiltered'] = $totalFiltered;
        // }

        // return response()->json($response);

        if ($request->ajax()) {
            $data = Transaksi::with('user');
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('username', function($row){
                        return $row->user->username;
                    })
                    ->addColumn('tgl_transaksi',function($row){
                        return date('d/m/Y H:i',strtotime($row->tgl_transaksi));
                    })
                    ->addColumn('total',function($row){
                        return number_format($row->total);
                    })
                    ->addColumn('action', function($row){
     
                           $btn = '
                           <a href="javascript:void(0)" class="btn btn-warning"><i class="fas fa-file"></i></a>
                           <a href="'.url('transaksi/edit/'.$row->id_transaksi).'" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                           <a href="javascript:void(0)" class="btn btn-danger" onclick="deleteTransaksi('.$row->id_transaksi.')"><i class="fas fa-trash"></i></a>
                           ';
       
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }

    public function list()
    {
        $data = [
            'title' => 'Data Transaksi',
            'content' => 'transaksi_list'
        ];
        return view('layout.index',['data' => $data]);
    }

    public function edit($id_transaksi)
    {
        $data = [
            'title' => 'Edit Transaksi',
            'content' => 'transaksi_edit',
            'item' => Item::with('kategori')->get(),
            'transaksi' => Transaksi::with('user')->find($id_transaksi),
            'transaksi_detail' => TransaksiDetail::with('item.kategori')->where('id_transaksi',$id_transaksi)->get()
        ];

        return view('layout.index',['data' => $data]);
    }

    public function update(Request $request, $id_transaksi)
    {
        $trans = Transaksi::find($id_transaksi);
        $trans->total = $request->input('total');
        if($trans->save()){
            if($request->has('id_item')){
                //Delete Detail 
                TransaksiDetail::where('id_transaksi',$id_transaksi)->forceDelete();
                foreach ($request->input('id_item') as $i => $td) {
                    $transDetail = new TransaksiDetail;
                    $transDetail->id_item = $td;
                    $transDetail->id_transaksi = $trans->id_transaksi;
                    $transDetail->qty = $request->input('qty')[$i];
                    $transDetail->subtotal = $request->input('subtotal')[$i];
                    $transDetail->save();
                }
                $checkTrans = TransaksiDetail::where('id_transaksi',$trans->id_transaksi)->first();
                if($checkTrans){
                    $this->calculateStok($trans->id_transaksi,2);
                    $this->calculateStok($trans->id_transaksi,1);
                    return redirect()->back()->with('success','Berhasil update transaksi!');
                }else{
                    return redirect()->back()->with('error','Gagal update transaksi!');
                }
            }else{
                return redirect()->back()->with('error','Detail transaksi tidak boleh kosong!');
            }
        }else{
            return redirect()->back()->with('error','Terjadi kesalahan update transaksi! coba lagi.');
        }
    }

    public function nota($id)
    {
        $q = Transaksi::with('user')->find($id);
        $data = [
            'title' => 'Nota Transaksi',
            'content' => 'transaksi_nota',
            'data' => $q
        ];
        return view('layout.index',['data' => $data]);
    }

    public function destroy($id)
    {
        $q = Transaksi::find($id);
        if(!$q)
            return response(['status' => 500, 'message' => 'Transaksi tidak ditemukan!']);

        if($q->delete()){
            $this->calculateStok($id,2);
            $detail = TransaksiDetail::where('id_transaksi',$id)->delete();
            if($detail){
                return response([
                    'status' => 200,
                    'message' => 'Berhasil menghapus Transaksi!'
                ]);
            }else{
                return response([
                    'status' => 500,
                    'message' => 'Gagal menghapus Transaksi! Detail error'
                ]);
            }
        }else{
            return response([
                'status' => 500,
                'message' => 'Gagal menghapus Transaksi!'
            ]);
        }
    }

    public function calculateStok($id_transaksi,$type)
    {
        if($type == 1){
            $item_sold = TransaksiDetail::where('id_transaksi',$id_transaksi)->get();
            foreach ($item_sold as $is) {
                $update = Item::find($is->id_item);
                $stok_now = $update->stok_item - $is->qty;
                $update->stok_item = $stok_now;
                $update->save();
            }
        }

        if($type == 2){
            $item_sold_delete = TransaksiDetail::where('id_transaksi',$id_transaksi)->get();
            foreach ($item_sold_delete as $isd) {
                $update = Item::find($isd->id_item);
                $stok_now = $update->stok_item + $isd->qty;
                $update->stok_item = $stok_now;
                $update->save();
            }
        }
    }

}
