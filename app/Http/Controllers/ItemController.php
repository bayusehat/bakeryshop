<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Kategori;
use App\Models\TransaksiDetail;
use Illuminate\Support\Facades\Validator;
use Datatables;

class ItemController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Item List',
            'content' => 'item',
            'kategori' => Kategori::all(),
            'notif_expired' => Item::where('expired_item','>',date('Y-m-d'))->orderBy('expired_item','desc')->limit(5)->get()
        ];

        return view('layout.index',['data'=>$data]);
    }

    public function loadData(Request $request)
    {
        // $status = $request->get('status');

        // $whereLike = [
        //     'nama_item',
        //     'nama_kategori'
        // ];

        // $start  = $request->input('start');
        // $length = $request->input('length');
        // $order  = $whereLike[$request->input('order.0.column')];
        // $dir    = $request->input('order.0.dir');
        // $search = $request->input('search.value');

        // $totalData = Item::with('kategori')->count();
        // if (empty($search)) {
        //     $queryData = Item::with('kategori')
        //         ->offset($start)
        //         ->limit($length)
        //         ->orderBy($order, $dir)
        //         ->get();
        //     $totalFiltered = Item::with('kategori')->count();
        // } else {
        //     $queryData = Item::with(['kategori' => function($ktg) use ($search){
        //         $ktg->orWhere('nama_kategori','like',"%{$search}%");
        //         }])
        //         ->where(function($query) use ($search) {
        //             $query->where('nama_item', 'like', "%{$search}%");
        //         })
        //         ->offset($start)
        //         ->limit($length)
        //         ->orderBy($order, $dir)
        //         ->get();
        //     $totalFiltered = Item::with(['kategori' => function($ktg) use ($search){
        //         $ktg->orWhere('nama_kategori','like',"%{$search}%");
        //         }])
        //         ->where(function($query) use ($search) {
        //             $query->where('nama_item', 'like', "%{$search}%");
        //         })
        //         ->offset($start)
        //         ->count();
        // }

        // $response['data'] = [];
        // if($queryData <> FALSE) {
        //     $nomor = $start + 1;
        //     foreach ($queryData as $val) {
        //             $response['data'][] = [
        //                 $nomor,
        //                 $val->nama_item,
        //                 $val->kategori->nama_kategori,
        //                 number_format($val->harga_item),
        //                 $val->stok_item,
        //                 $this->itemSold($val->id_item),
        //                 $val->expired_item,
        //                 $this->checkExpired($val->expired_item),
        //                 '
        //                 <a href="javascript:void(0)" class="btn btn-primary" onclick="editItem('.$val->id_item.')"><i class="fas fa-edit"></i></a>
        //                 <a href="javascript:void(0)" class="btn btn-danger" onclick="deleteItem('.$val->id_item.')"><i class="fas fa-trash"></i></a>
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
            $data = Item::with('kategori');
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('nama_kategori', function($row){
                        return $row->kategori->nama_kategori;
                    })
                    ->addColumn('harga',function($row){
                        return number_format($row->harga_item);
                    })
                    ->addColumn('check',function($row){
                        return $this->checkExpired($row->expired_item);
                    })
                    ->addColumn('terjual', function($row){
                        return $this->itemSold($row->id_item);
                    })
                    ->addColumn('action', function($row){
     
                           $btn = '<a href="javascript:void(0)" class="btn btn-primary" onclick="editItem('.$row->id_item.')"><i class="fas fa-edit"></i></a>
                           <a href="javascript:void(0)" class="btn btn-danger" onclick="deleteItem('.$row->id_item.')"><i class="fas fa-trash"></i></a>';
       
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }

    public function createItem()
    {
        $data = [
            'title' => 'Insert New Item',
            'content' => 'item_insert'
        ];
        return view('layout.index',['data' => $data]);
    }

    public function insertItem(Request $request)
    {
        $rules = [
            'nama_item' => 'required',
            'stok_item' => 'required',
            'harga_item' => 'required',
            'id_kategori' => 'required',
            'expired_item' => 'required'
        ];

        $isValid = Validator::make($request->all(),$rules);

        if($isValid->fails()){
            return response([
                'status' =>  400,
                'errors' => $isValid->errors()
            ]);
        }else{

            $item = new Item;
            $item->nama_item = $request->input('nama_item');
            $item->stok_item = $request->input('stok_item');
            $item->harga_item = $request->input('harga_item');
            $item->id_kategori = $request->input('id_kategori');
            $item->expired_item = $request->input('expired_item');
            $item->status_item = $this->checkExpired($request->input('expired_item'));

            if($item->save()){
                return response([
                    'status' => 200,
                    'message' => 'Item created successfully!'
                ]);
            }else{
                return response([
                    'status' => 500,
                    'message' => 'Failed to insert a new Item, try again!'
                ]);
            }
        }
    }

    public function editItem($id)
    {
        $data = Item::find($id);
        return response($data);
    }

    public function updateItem(Request $request, $id)
    {
        $rules = [
            'nama_item' => 'required',
            'stok_item' => 'required',
            'harga_item' => 'required',
            'id_kategori' => 'required',
            'expired_item' => 'required'
        ];

        $isValid = Validator::make($request->all(),$rules);

        if($isValid->fails()){
            return response([
                'status' => 400,
                'errors' => $isValid->errors()
            ]);
        }else{

            $item = Item::find($id);
            if($item){
                $item->nama_item = $request->input('nama_item');
                $item->id_kategori = $request->input('id_kategori');
                $item->harga_item = $request->input('harga_item');
                $item->expired_item = $request->input('expired_item');
                $item->stok_item = $request->input('stok_item');
                $item->status_item = $this->checkExpired($request->input('expired_item'));

                if($item->save()){
                    $this->syncStok($id);
                    return response([
                        'status' => 200,
                        'message' => 'Item updated successfully!'
                    ]);
                }else{
                    return response([
                        'status' => 500,
                        'message' => 'Failed to update Item!'
                    ]);
                }
            }else{
                return response([
                    'status' => 500,
                    'message' => 'Item not found!'
                ]);
            }
        }
    }

    public function deleteItem($id)
    {
        $item = Item::find($id);
        if(!$item)
            return response(['status' => 401,'message' => 'Item not found']);

        if($item->delete()){
            return response(['status' => 200, 'message' => 'Item deleted successfully']);
        }else{
            return response(['status' => 500, 'message' => 'Failed to delete item, try again!']);
        }
    }

    public function syncStok($id_item)
    {
        $jml_item = 0;
        $item = Item::find($id_item);
        $item_sold = TransaksiDetail::where('id_item', $id_item)->get();
        foreach ($item_sold as $is) {
            $jml_item += $is->qty;
        }
        $stok_update = $item->stok_item - $jml_item;
        $item->stok_item = $stok_update;
        $item->save();
    }

    public function getItem($id)
    {
        $data = Item::with('kategori')->find($id);
        return response($data);
    }

    public static function checkExpired($dateExpired)
    {
        $expired_date = strtotime($dateExpired);
        $notice = strtotime(date("Y-m-d", strtotime("-2 week", $expired_date)));
        $now = strtotime(date('Y-m-d'));
        if($now >= $expired_date){
            $status = 'Expired';
        }else if($now >= $notice && $notice <= $expired_date){
            $datediff = $now-$expired_date; 
            $countDay = round($datediff / (60 * 60 * 24));
            $status = 'Become Expired ('.-1 * $countDay.' more days)';
        }else{
            $status = 'Not Expired';
        }

        return $status;
    }

    public function itemSold($id_item)
    {
        $jml_sold = 0;
        $sold = TransaksiDetail::where('id_item',$id_item)->get();
        foreach ($sold as $sd) {
            $jml_sold += $sd->qty;
        }

        return $jml_sold;
    }

}
