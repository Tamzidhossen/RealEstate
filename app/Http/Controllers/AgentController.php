<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class AgentController extends Controller
{
    public function AgentDashboard(){
        return view('agent.index');

    } // End Method

    
    public function AgentLogin(){
        return view('agent.agent_login');
        
    } // End Method
    
    public function AgentRegister(Request $request) {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'role' => 'agent',
            'status' => 'inactive',
        ]);
        
        event(new Registered($user));
        Auth::login($user);
        
        return redirect()->route('agent.dashboard');
    } //End Method

    public function AgentLogout(Request $request){
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $notification = array(
            'message' => "Agent Logout Successfully",
            'alert-type' => 'success'
        );

        return redirect('/agent/login')->with($notification);
    } // End Method

    public function AgentProfile(){
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('agent.agent_profile_view', compact('profileData'));
    } // End Method

    public function AgentProfileStore(Request $request){
        $profileData = User::find(Auth::user()->id);

        $profileData->username = $request->username;
        $profileData->name = $request->name;
        $profileData->email = $request->email;
        $profileData->phone = $request->phone;
        $profileData->address = $request->address;

        // if($request->photo){
        //     $file = $request->photo;
        //     $delete_form = public_path('uploads/agent_images/'. $profileData->photo);
        //     @unlink($delete_form);
        //     $extension = $file->extension();
        //     $file_name = uniqid().'.'. $extension;

        //     $file->move(public_path('uploads/agent_images'), $file_name);
        //     $profileData['photo'] = $file_name;
        // }
        // $profileData->save();
        
        if(Auth::user()->photo !=null){
            $delete = public_path('uploads/agent_images/'.Auth::user()->photo);
            unlink($delete);
        }

        $photo = $request->photo;
        $extension =  $photo->extension();
        $file_name = uniqid().'.'.$extension;
        // echo $file_name;

        $manager = new ImageManager(new Driver());
        $image = $manager->read($photo);
        // $image->resize(70, 40);
        $image->save(public_path('uploads/agent_images/'.$file_name));

        User::find(Auth::id())->update([
            'photo'=>$file_name,
        ]);

        $notification = array(
            'message' => "Agent Profile Updated Successfully",
            'alert-type' => 'success'
        );
        return back()->with($notification);
    } // End Method

    public function AdminChangePassword(){
        $profileData = User::find(Auth::user()->id);
        return view('agent.agent_change_password', compact('profileData'));
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
                'message' => "Agent Password Does not Match!",
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
    }  // End Method
}
