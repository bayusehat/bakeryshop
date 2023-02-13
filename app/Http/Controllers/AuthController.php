<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Auth;
use Illuminate\Support\Facades\Session;
use Datatables;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function doLogin(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        $isValid = Validator::make($request->all(),$rules);//validasi untuk login 

        if($isValid->fails()){
            return redirect()->back()->withErrors($isValid->errors());
        }else{
            $check = Auth::where('username',$username);
            if($check->count() > 0){
                $data = $check->first();
                if($data){
                    if(Hash::check($password,$data->password)){
                        if($data->status == 0){
                            $session =[
                                'id_user' => $data->id_user,
                                'username' => $data->username,
                                'is_logged' => true,
                                'role' => $data->role
                            ];
                            session($session);
                            return redirect('/dashboard');
                        }else{
                            return redirect()->back()->with('error','User tidak aktif!');
                        }
                    }else{
                        return redirect()->back()->with('error','Password yang anda masukkan salah!');
                    }
                }else{
                    return redirect()->back()->with('error','User Error!');
                }
            }else{
                return redirect()->back()->with('error','User tidak ditemukan!');
            }
        }
    }

    public function createUser(Request $request)
    {
        $data = [
            'username' => 'admin',
            'password' => Hash::make('hello123#'),
            'no_hp' => '089333888999'
        ];
        $insert = Auth::insert($data);
        if($insert){
            echo 'Sukses';
        }else{
            echo 'Gagal';
        }
    }

    public function doLogout()
    {
        Session::put('is_logged',false);
        Session::save();
        return redirect('/');
    }

    public function user()
    {
        $data = [
            'title' => 'Data User',
            'content' => 'user_list',
        ];

        return view('layout.index',['data' => $data]);
    }

    public function loadData(Request $request)
    {
        $response['data'] = [];
        $query = Auth::get();

        foreach ($query as $q) {
            if($q->status == 0){
                $status = '<a href="javascript:void(0)" class="btn btn-success" onclick="changeStatus('.$q->status.','.$q->id_user.')"><i class="fas fa-check"></i> Active</a>';
            }else{
                $status = '<a href="javascript:void(0)" class="btn btn-danger" onclick="changeStatus('.$q->status.','.$q->id_user.')"><i class="fas fa-timex"></i> Not Active</a>';
            }
            $response['data'][] = [
                $q->username,
                $q->no_hp,
                $status,
                '
                <a href="javascript:void(0)" class="btn btn-warning" onclick="editUser('.$q->id_user.')"><i class="fas fa-edit"></i></a>
                <a href="javascript:void(0)" class="btn btn-danger" onclick="deleteUser('.$q->id_user.')"><i class="fas fa-trash"></i></a>
                '
            ];
        }

        return response($response);
    }

    public function insertUser(Request $request)
    {
        $checkUser = Auth::where('username',$request->input('username'))->first();
        if($checkUser)
            return response(['status' => 500,'message' => 'Username already registered.']);

        $rules = [
            'username' => 'required',
            'no_hp' => 'required',
            'role' => 'required'
        ];

        $isValid = Validator::make($request->all(),$rules);

        if($isValid->fails()){
            return response([
                'status' => 401,
                'errors' => $isValid->errors()
            ]);
        }else{
            $user = new Auth;
            $user->username = $request->input('username');
            $user->no_hp = $request->input('no_hp');
            $user->password = Hash::make('BakeryShop2022');
            $user->status = 0;
            $user->role = $request->input('role');
            
            if($user->save()){
                return response([
                    'status' => 200,
                    'message' => 'New User added successfully'
                ]);
            }else{
                return response([
                    'status' => 500,
                    'message' => 'Failed to add user, please try again'
                ]);
            }
        }
    }

    public function editUser($id)
    {
        $user = Auth::find($id);
        return response($user);
    }

    public function updateUser(Request $request, $id)
    {
        $checkUser = Auth::where('username',$request->input('username'))->first();
        if($checkUser)
            return response(['status' => 500,'message' => 'Username already registered.']);

        $rules = [
            'username' => 'required',
            'no_hp' => 'required',
            'role' => 'required'
        ];

        $isValid = Validator::make($request->all(),$rules);

        if($isValid->fails()){
            return response([
                'status' => 401,
                'errors' => $isValid->errors()
            ]);
        }else{
            $user = Auth::find($id);
            $user->username = $request->input('username');
            $user->no_hp = $request->input('no_hp');
            $user->status = $request->input('status');
            $user->role = $request->input('role');
            
            if($user->save()){
                return response([
                    'status' => 200,
                    'message' => 'User updated successfully'
                ]);
            }else{
                return response([
                    'status' => 500,
                    'message' => 'Failed to update user, please try again'
                ]);
            }
        }
    }

    public function destroyUser($id)
    {
        $user = Auth::find($id);
        if(!$user)
            return response(['status' => 401, 'message' => 'User not found.']);
        
        if($user->delete()){
            return response([
                'status' => 200,
                'message' => 'User deleted successfully'
            ]);
        }else{
            return response([
                'status' => 500,
                'message' => 'Failed delete user!'
            ]);
        }
    }

    public function changeStatus($status, $id)
    {
        $user = Auth::find($id);
        if(!$user)
            return response(['status' => '401', 'message' => 'User not found!']);

        if($status == 1){
            $user->status = 0;
        }else{
            $user->status = 1;
        }

        if($user->save()){
            return response(['status' => 200,'message' => 'Status changed!']);
        }else{
            return response(['status' => 500, 'message' => 'Failed change status!']);
        }
    }
}
