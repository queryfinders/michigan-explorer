<?php

namespace App\Helpers;

use App\Models\Role;
use Illuminate\Support\Facades\Session;

class AccessRights

{
    public static function accessRights($accessdata)
    {
        $access_rights =[];
        $user = session()->get('UserDetails');
        if(isset($user->role_id)){
            $role = Role::where('id',$user->role_id)->first();
            if(isset($role->rights_permission)){
                $access_rights = json_decode($role->rights_permission);
            }
        }
        if(is_array($access_rights) && count($access_rights) > 0){
            if(in_array($accessdata,$access_rights) && count($access_rights) > 0){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
}