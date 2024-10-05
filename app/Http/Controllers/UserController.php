<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

use function Laravel\Prompts\select;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = User::query()->where('users.isDeleted',false)->leftjoin('roles', function ($join){
                $join->on('users.role_id', '=', 'roles.id');
            })->select('users.id','users.name','users.email','users.role_id','users.isDeleted','users.isDisable','roles.name as role_name');
            return DataTables::of($roles)
                ->addColumn('action', function ($roles) {

                    $showBtn =  '<button ' .
                                    ' class="btn btn-outline-info" ' .
                                    ' onclick="showData(' . $roles->id . ',false)">Show' .
                                '</button> ';
                    $editBtn =  '<button ' .
                        ' class="btn btn-outline-success" ' .
                        ' onclick="showData(' . $roles->id . ',true)">Edit' .
                        '</button> ';
                    
                    $DisableBtn =  '<button ' .
                        ' class="btn btn-outline-warning" ' .
                        ' onclick="disableUser(' . $roles->id . ',`'.($roles->isDisable?"Un Dissable":'Dissable').'`)">'.($roles->isDisable?'Un ':'').'Disable' .
                        '</button> ';

                    $deleteBtn =  '<button ' .
                        ' class="btn btn-outline-danger" ' .
                        ' onclick="destroyData(' . $roles->id . ')">Delete' .
                        '</button> ';

                    return $showBtn . $editBtn . $deleteBtn.$DisableBtn;
                })
                ->rawColumns(
                    [
                        'action',
                    ]
                )
                ->make(true);
        }
        return view('/user/user');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role_id' => 'required|numeric',
        ]);
        $status = true;
        $message = "";
        DB::beginTransaction();
        $data=null;
        try {       
            $User = new User();
            $User->name = $request->name;
            $User->email = $request->email;
            $User->password = Hash::make($request->password);
            $User->isDeleted = false;
            $User->isDisable = false;
            $roleData =null;
            if(!empty($request->role_id)){
                $roleData = Role::where('id', $request->role_id)->first();
                if (is_null($roleData)) throw new Exception('Role Not Exist');
            }
            $User->role_id = (is_null($roleData))?null:$roleData->id;
            $User->save();
            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            $status = false;
            $message = $e->getMessage()."Line No : ". $e->getLine();
        }
        return response()->json(['status' => $status, 'message' => $message,'data'=>$data]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = User::where('users.id',$id)->leftjoin('roles', function ($join){
            $join->on('users.role_id', '=', 'roles.id');
        })->select('users.id','users.name','users.email','users.role_id','users.isDeleted','users.isDisable','roles.name as role_name')->first();

        return response()->json(['status' => "success", 'data' => $role]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'role_id' => 'required|numeric',
        ]);
        $status = true;
        $message = "";
        DB::beginTransaction();
        $data=null;
        try {       
            $User = User::find($id);
            $User->name = $request->name;
            $User->email = $request->email;
            $roleData =null;
            if(!empty($request->role_id)){
                $roleData = Role::where('id', $request->role_id)->first();
                if (is_null($roleData)) throw new Exception('Role Not Exist');
            }
            $User->role_id = (is_null($roleData))?null:$roleData->id;
            $User->save();
            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            $status = false;
            $message = $e->getMessage()."Line No : ". $e->getLine();
        }
        return response()->json(['status' => $status, 'message' => $message,'data'=>$data]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $User = User::find($id);
        $User->isDeleted = true;
        $User->save();
        return response()->json(['status' => "success"]);
    }
    public function disable(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric',
        ]);
        $User = User::find($request->id);
        $User->isDisable = !($User->isDisable);
        $User->save();
        return response()->json(['status' => "success"]);
    }

    public function getRoles()
    {
        $role = Role::get();

        return response()->json(['status' => true, 'data' => $role]);
    }
}
