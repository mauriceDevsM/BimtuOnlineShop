<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    //
    public function AdminDashboard(){
        return view('admin.index');
    }// End method AdminDashboard
    

    public function AdminLogin(){
        return view('admin.admin_login');
    }


    public function AdminDestroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
    //End Method

    public function AdminProfile(){
        $id = Auth::user()->id;
        $adminData = User::find($id);
        return view('admin.admin_profile_view', compact('adminData'));
    }
    //End Method
    
    public function AdminProfileStore(Request $request){
        $id = Auth::user()->id;
        $data = User::find($id);
        $data -> name = $request->name;
        $data -> email = $request->email;
        $data -> phone = $request->phone;
        $data -> address = $request->address;

        if($request->file('photo')){
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images'.$data->photo));
            $filename = date('YdmHi').$file->getClientoriginalName();
            $file -> move(public_path('upload/admin_images'), $filename);
            $data['photo'] = $filename;
        }

        $data->save();

        $notification = array(
            'message' => 'Admin profile updated successfully',
            'alert-type' => 'success',
        );

        return redirect()->back()-> with($notification);

    }//End Method

    public function AdminChangePassword(Request $request){
        return view('admin.admin_change_password');
    }//End Method

    public function AdminUpdatePassword(Request $request){
        //Validation
        $request ->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        //Match the old password
        if(!Hash::check($request->old_password, auth::user()->password)){
            //Code
            return back()->with('error', "Old password is invalid!!");
        }
        //update the new password
        User::whereId(auth::user()->id)-> update([
            'password' => Hash::make($request->new_password)
        ]);
        return back()->with("status", "Password updated successfully") ;
    
    }//end Method
}
