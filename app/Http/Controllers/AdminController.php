<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function AdminDashboard(){
        return view('admin.index');

    } // End Method

    public function AdminLogout(Request $request){
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    } // End Method

    public function AdminLogin(){
        return view('admin.admin_login');

    }

    public function AdminProfile(){
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('admin.admin_profile_view', compact('profileData'));
    }

    public function AdminProfileStore(Request $request){
        $profileData = User::find(Auth::user()->id);
        
        if($request->photo != ''){
            if($profileData->photo != ''){
                $delete_form = public_path('uploads/admin_images/'. $profileData->photo);
                unlink($delete_form);
            }

            $file = $request->photo;
            $extension = $file->extension();
            $file_name = uniqid().'.'. $extension;
            // return $file_name;

            $file->move(public_path('uploads/admin_images'), $file_name);
        }
        User::find(Auth::user()->id)->update([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address'=> $request->address,
            'photo' => $file_name,
        ]);

        $notification = array(
            'message' => "Admin Profile Updated Successfully",
            'alert-type' => 'success'
        );
        return back()->with($notification);
    }
}
