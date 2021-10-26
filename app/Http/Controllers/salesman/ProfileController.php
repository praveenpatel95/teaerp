<?php

namespace App\Http\Controllers\salesman;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $data = Auth::user();
        return view('salesman.profile.index', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = User::find($id);
        if ($request->general == 1) {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
            ]);
            $data->name = $request->name;
            $data->email = $request->email;
        } elseif ($request->general == 2) {
            $request->validate([
                'oldpassword' => 'required',
                'password' => 'required|min:6|confirmed',
            ]);
            $user = Auth::user();
            $oldpassword = $user->password;
            if (Hash::check($request->oldpassword, $oldpassword)) {
                $user->password = Hash::make($request->password);
                $user->save();
            } else {
                session()->flash('danger_msg', 'Something wents wrong');
                return redirect()->back();
            }
        }
        $data->save();
        session()->flash('success_msg', 'Updated successfully');
        return redirect()->back();
    }
}
