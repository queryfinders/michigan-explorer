<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\AccessRights;;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{

    public function role(Request $request){
        return view('new_content.user.role_list');
    }

    public function index_role(Request $request)
    {
        $role= Role::all();
        return response()->json(['role' => $role]);
    }

    public function create_role(Request $request)
    {
        $rights = AccessRights::all();
        return view('new_content.user.add_role',compact('rights'));
    }

    public function edit_role($id){
        $role = Role::find($id);
        $selectedRights = json_decode($role->rights_permission);
        $rights = AccessRights::all();
        if($role){
            return view('new_content.user.add_role',compact('role','rights','selectedRights'));
        }
    }

    public function store_role(Request $request ,$id = null)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required'
        ]);

        if ($validator->fails())
        {
            return response()->json(["status"=>404,'errors'=>$validator->errors()]);
        }
        
        if ($id) {
            $update_user = Role::find($id);
            $update_user->role = $request->role;
            $update_user->rights_permission = json_encode($request->rights_permission);
            $update_user->save();
            return redirect()->route('role')->withSuccess(__('Role updated successfully.'));
        }
        else{
            $insert_role = Role::create([
                'role' => $request->role,
                'rights_permission' => json_encode($request->rights_permission)
            ]);
            return redirect()->route('role')->withSuccess(__('Role created successfully.'));
        }
    }

    public function delete_role(Request $request,$id)
    {
            $delete_user = Role::find($id);
            $delete_user->delete();
            return response()->json(["status"=>200, 'success' => 'User deleted successfully.']);
    }

}
