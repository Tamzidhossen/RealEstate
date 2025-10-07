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

        $notification = array(
            'message' => "Admin Logout Successfully",
            'alert-type' => 'success'
        );

        return redirect('/admin/login')->with($notification);
    } // End Method

    public function AdminLogin(){
        return view('admin.admin_login');

    } // End Method

    public function AdminProfile(){
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('admin.admin_profile_view', compact('profileData'));
    }  // End Method

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
    } // End Method
    
    public function AdminChangePassword(){
        $profileData = User::find(Auth::user()->id);
        return view('admin.admin_change_password', compact('profileData'));
    } // End Method

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
    }   // End Method

    //////// Agent All Method From Admin //////////
    public function AllAgent(){
        $agents = User::where('role', 'agent')->get();
        return view('backend.agentuser.all_agent', compact('agents'));
    }   // End Method

    public function AddAgent(){
        return view('backend.agentuser.add_agent');
    }   // End Method

    public function StoreAgent(Request $request) {
        User::insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role' => 'agent',
            'status' => 'active',
        ]);

        $notification = array(
            'message' => "Agent Add Successfully",
            'alert-type' => 'success'
        );
        return redirect()->route('all.agent')->with($notification);
    } // End Method

    public function EditAgent($id){
        $agent = User::findOrFail($id);
        return view('backend.agentuser.edit_agent', compact('agent'));
    } // End Method

    public function UpdateAgent(Request $request, $id) {
        User::findOrFail($id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        $notification = array(
            'message' => "Agent Updated Successfully",
            'alert-type' => 'success'
        );
        return redirect()->route('all.agent')->with($notification);
    } // End Method

    public function DeleteAgent($id) {
        User::findOrFail($id)->delete();

        $notification = array(
            'message' => "Agent Delete Successfully",
            'alert-type' => 'success'
        );
        return redirect()->route('all.agent')->with($notification);
    } // End Method
}
