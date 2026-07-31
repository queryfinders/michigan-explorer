<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use App\Traits\Sortable;
use App\Traits\Exportable;

class UserController extends Controller
{
    use Sortable, Exportable;

    public function user(Request $request){
        if (!\App\Helpers\AccessRights::accessRights('users.list')) {
            $users = collect([]);
            $users = new \Illuminate\Pagination\LengthAwarePaginator($users, 0, 10);
            if ($request->ajax()) {
                return view('new_content.user._table', compact('users'))->render();
            }
            return view('new_content.user.user_list', compact('users'));
        }

        $query = AdminUser::leftJoin('role','admin_user.role_id','=','role.id')
            ->select(['admin_user.id', 'admin_user.name', 'admin_user.email_id', 'admin_user.contact_no', 'admin_user.job_title', 'admin_user.role_id', 'admin_user.is_active', 'admin_user.profile_url', 'admin_user.created_at', 'role.role']);
            
        // Filtering
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('admin_user.name', 'like', "%{$search}%")
                  ->orWhere('admin_user.email_id', 'like', "%{$search}%")
                  ->orWhere('admin_user.contact_no', 'like', "%{$search}%");
            });
        }
        if ($request->filled('role')) {
            $query->where('admin_user.role_id', $request->role);
        }
        if ($request->filled('status')) {
            $query->where('admin_user.is_active', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('admin_user.created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('admin_user.created_at', '<=', $request->date_to);
        }
            
        $query = $this->applySorting($query, ['admin_user.id', 'name', 'email_id', 'contact_no', 'job_title', 'role', 'is_active', 'created_at'], 'admin_user.id', 'desc');
        
        // Export
        if ($request->has('export')) {
            return $this->exportData($query, $request->export, 'users_export', function ($user) {
                return [
                    'ID' => $user->id,
                    'Name' => $user->name,
                    'Email' => $user->email_id,
                    'Phone' => $user->contact_no,
                    'Job Title' => $user->job_title,
                    'Role' => $user->role,
                    'Status' => $user->is_active ? 'Active' : 'Inactive',
                    'Registration Date' => $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : '',
                ];
            });
        }
        
        $users = $query->paginate(10);

        if ($request->ajax()) {
            return view('new_content.user._table', compact('users'))->render();
        }

        $roles = Role::all();

        return view('new_content.user.user_list', compact('users', 'roles'));
    }

    public function index_user(Request $request)
    {
        // deprecated, kept for legacy if any JS directly calls it.
        $user= AdminUser::leftJoin('role','admin_user.role_id','=','role.id')->select(['admin_user.id', 'admin_user.name', 'admin_user.email_id', 'admin_user.contact_no', 'admin_user.job_title', 'admin_user.role_id', 'admin_user.is_active', 'admin_user.profile_url', 'role.role'])->orderBy('admin_user.id', 'desc')->get();
        return response()->json(['user' => $user]);
    }

    public function checkEmail(Request $request) {
        $email_id = $request->input('email_id');
        $exists = AdminUser::where('email_id', $email_id)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function create_user(Request $request)
    {
        $role = Role::all();
        return view('new_content.user.add_user',compact('role'));
    }

    public function edit_user($id){
        $user = AdminUser::find($id);
        $role = Role::all();
        if($user){
            return view('new_content.user.add_user',compact('role','user'));
        }
    }

    public function store_user(Request $request ,$id = null)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email_id' => 'required|email',
            'contact_no' => 'required|min:10|max:10',
            'job_title' => 'required',
            'profile_url' => 'nullable',
            'role_id' => 'required'
        ]);

        if ($validator->fails())
        {
            return response()->json(["status"=>404,'errors'=>$validator->errors()]);
        }
        $user = session()->get('UserDetails');
        $profile_url = null;
        if($request->hasFile('profile_url'))
        {
            $image = $request->file('profile_url');
            $imageName = 'journal_of_supply_chain_'.$request->name.'_'. time(). '.' .$image->getClientOriginalExtension();
            Storage::putFileAs("/user/", $image, $imageName);
            $profile_url = '/user/' . $imageName;
        }
        if ($id) {
            $update_user = AdminUser::find($id);
            $update_user->name = $request->name;
            $update_user->email_id = $request->email_id;
            $update_user->contact_no = $request->contact_no;
            $update_user->job_title = $request->job_title;
            $update_user->profile_url = $profile_url != null ? $profile_url : $update_user->profile_url;
            $update_user->role_id = $request->role_id;
            $update_user->updated_by = $user->id;
            $update_user->save();
            return redirect()->route('user-add')->withSuccess(__('User updated successfully.'));
        }
        else{
            $insert_login = AdminUser::create([
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'email_id' => $request->email_id,
                'contact_no' => $request->contact_no,
                'job_title' => $request->job_title,
                'profile_url' => $profile_url,
                'role_id' => $request->role_id,
                'created_by' => $user->id
            ]);
            return redirect()->route('user-add')->withSuccess(__('User created successfully.'));
        }
        // return response()->json(["status"=>200, 'success' => 'User Successfully added.']);
    }

    public function delete_user(Request $request,$id)
    {
            $user = session()->get('UserDetails');
            $delete_user = AdminUser::find($id);
            $delete_user->deleted_by =  $user->id;
            $delete_user->save();
            $delete_user->delete();
            return response()->json(["status"=>200, 'success' => 'User deleted successfully.']);
    }

    public function status_user($id, $status)
    {
        $editStatus = AdminUser::find($id);
        if($editStatus)
        {
            $editStatus->is_active = $status == 1 ? 0 : 1;
            $editStatus->save();
        }
        return response()->json();
    }

}
