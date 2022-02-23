<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:ROLE_ADMIN');
    }

    public function index()
    {
        $users = User::all();
        return view('user.index',['users'=>$users]);
    }

    public function create()
    {
        return view('user.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        if($validated){
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            \DB::table('role_user')->insert([
                'role_id' => 2,
                'user_id' => $user->id,
            ]);
            return redirect()->route('user.index');
        }
    }

    public function edit($id)
    {
        $user = User::find($id);

        return view('user.edit',['user'=>$user,'id'=>$id]);
    }

    public function update($id, Request $request)
    {
        $user = User::find($id);
        if($user){
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
            ]);
            if($validated){
                $user->name = $request->name;
                $user->email = $request->email; 
                $user->save();

                return redirect()->route('user.index');
            }
        }
        return view('user.edit',['user'=>$user]);
    }

    public function editPass($id)
    {
        return view('user.edit_pass',['id'=>$id]);
    }

    public function updatePass($id, Request $request)
    {
        $user = User::find($id);
        if($user){
            $validated = $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            if($validated){
                $user->password = Hash::make($request->password);
                $user->save();

                return redirect()->route('user.index')->with('success', 'Password Berhasil Diubah');;
            }
        }
        return view('user.edit_pass',['user'=>$user]);
    }

    public function delete($id)
    {
        $user = User::find($id);
        if ($user){
            $user->delete();
            \DB::table('role_user')->where('user_id',$user->id)->delete();

            return redirect()->route('user.index')->with('success', 'Data telah dihapus.');
        }

        return redirect()->route('user.index')->with('warning', 'Data gagal dihapus.');
    }

}