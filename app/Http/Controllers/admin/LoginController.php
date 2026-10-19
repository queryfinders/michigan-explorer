<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\PasswordReset;
use App\Models\Role;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
  public function login()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('new_content.admin.login', ['pageConfigs' => $pageConfigs]);
  }

  public function login_details(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'email_id' => 'required',
        'password' => 'required', 
      ]);

        if ($validator->fails()){
            return Redirect::to("admin")->withInput()->withErrors($validator)->with('error', "EmailId & Password Required");
        }

          $result = AdminUser::where(['email_id' => $request->email_id])->first();
          
          if($result)
          {
            if(Hash::check($request->password, $result->password))
            {
                  // $role = Role::where('id',$result->role_id)->first();

                  $request->session()->put('ADMIN_LOGIN',true);
                  $request->session()->put('UserDetails',$result);
                  // $request->session()->put('permissions',$role->rights_permission);

                  return redirect()->intended('/dashboard'); 
              }else{
                
                  $request->session()->flash('error','Password is Wrong');
                  return redirect('admin');
            }
          }
          $request->session()->flash('error','Email address is wrong');
                  return redirect('admin');
          // return redirect("admin")->withErrors('Login details are not valid');
    }

    public function changepassword()
    {
      return view('new_content.admin.changepassword');
    }

    public function storeChangepassword(Request $request)
    {
      $validator = Validator::make($request->all(), [
          'oldpassword' => 'required',
          'newpassword' => 'required',
          'cnfpassword' => 'required|same:newpassword',
      ]);

      if ($validator->fails()){
          return Redirect::to("admin/changepassword")->withInput()->withErrors($validator)->with('error', "Enter Valid Passwords");
      }

      $credentials = $request->only('oldpassword');
      if($credentials){
          $UserDetails = session()->get('UserDetails');
          $admin = AdminUser::where('id','=',$UserDetails->id)->first();
          if(!Hash::check($request->oldpassword, $admin->password)){
              return redirect('admin/changepassword')->with('error' , 'Current password does not match');
          }
          else{
              if ($request->newpassword != $request->cnfpassword) {
                  return redirect('admin/changepassword')->with('error' , 'New and Confirm password does not match');
              }
              else{
                AdminUser::find($UserDetails->id)->update(['password'=> Hash::make($request->newpassword)]);
                  return redirect('admin')->with('success' , 'Password Has Been Changed...');
              }
          }
      }
    }

    public function forgot_password()
    {
      $pageConfigs = ['myLayout' => 'blank'];
      return view('new_content.admin.forgotpassword', ['pageConfigs' => $pageConfigs]);
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
          'email_id' => 'required|email|exists:admin_user,email_id'
        ]);

        $token = Str::random(64);
        PasswordReset::create([
          'email_id' => $request->email_id,
          'token' => $token,
          'created_at' => Carbon::now(),
      ]); 

      $action_link = route('reset-password',['token'=>$token,'email_id'=>$request->email_id]);
      $body = "We are received a request to reset the password for <b>QFS</b> account associated with ".$request->email_id.
      ". You can reset your password by clicking the link below";

      Mail::send('new_content.admin.email_forgot', ['action_link'=>$action_link, 'body' => $body], function($message) use ($request) {
        $message->from('developer.qfs510@gmail.com', 'QFS');
        $message->to($request->email_id)
        ->subject('Reset Password');
      });

      return back()->with('success','we have e-mailed your password reset link!');
    }

    public function reset_password(Request $request, $token=null)
    {
      $pageConfigs = ['myLayout' => 'blank'];
      return view('new_content.admin.resetpassword', ['pageConfigs' => $pageConfigs])->with(['token'=> $token, 'email_id' =>$request->email_id]);
    }

    public function reset(Request $request){
      $request->validate([
        'email_id' => 'required|email|exists:admin_user,email_id',
        'password' => 'required|confirmed',
        'password_confirmation' => 'required',
      ]);

      $check_token = PasswordReset::where(['email_id'=>$request->email_id,'token'=>$request->token])->first();

      if(!$check_token){
        return back()->withInput()->with('error','Invalid token');
      }
      else{
        AdminUser::where('email_id', $request->email_id)->update(['password'=>Hash::make($request->password)]);
        PasswordReset::where(['email_id'=>$request->email_id])->delete();
        return redirect()->route('login')->with('success','Your Password has been changed! you can login with new password.')->with('verifiedEmail',$request->email_id);
      }
    }
}