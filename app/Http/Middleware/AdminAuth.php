<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->session()->has('ADMIN_LOGIN')){
            $user = session()->get('UserDetails');
            if(isset($user->role_id)){
                $role = Role::where('id',$user->role_id)->first();
                if(isset($role->rights_permission) && $role->rights_permission != null){
                   
                }else{
                    return redirect('admin')->withErrors(__('You have no permission.'));
                }
            }
        }
        else{
                return redirect('admin');
        }
        return $next($request);
    }
}