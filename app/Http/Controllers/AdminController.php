<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        $profileData->username = $request->username;
        $profileData->name = $request->name;
        $profileData->email = $request->email;
        $profileData->phone = $request->phone;
        $profileData->address = $request->address;

        if($request->photo){
            
            
            $file = $request->photo;
            $delete_form = public_path('uploads/admin_images/'. $profileData->photo);
            unlink($delete_form);
            $extension = $file->extension();
            $file_name = uniqid().'.'. $extension;

            $file->move(public_path('uploads/admin_images'), $file_name);
            $profileData['photo'] = $file_name;
        }
        $profileData->save();

        $notification = array(
            'message' => "Admin Profile Updated Successfully",
            'alert-type' => 'success'
        );
        return back()->with($notification);
    }
    
    public function AdminChangePassword(){
        $profileData = User::find(Auth::user()->id);
        return view('admin.admin_change_password', compact('profileData'));
    }

    public function AdminUpdatePassword(Request $request){

        //validation
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed'
        ]);

        //Match The Old Password
        if(!Hash::check($request->old_password, Auth::user()->password)){
            $notification = array(
                'message' => "Admin Password Does not Match!",
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }

        //Update The New Password
        User::whereId(Auth::user()->id)->update([
            'password' => Hash::make($request->password),
        ]);

        $notification = array(
            'message' => "Password Update Successfully",
            'alert-type' => 'success'
        );
        return back()->with($notification);
    }
}
