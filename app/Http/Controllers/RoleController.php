<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModuleWithRole;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::query();
            return DataTables::of($roles)
                ->addColumn('action', function ($roles) {

                    // $showBtn =  '<button ' .
                    //                 ' class="btn btn-outline-info" ' .
                    //                 ' onclick="showData(' . $roles->id . ',false)">Show' .
                    //             '</button> ';
                    $editAccessModule =  '<button ' .
                        ' class="btn btn-outline-warning" ' .
                        ' onclick="editAccessModule(' . $roles->id . ',`' . $roles->name . '`)">Modules' .
                        '</button> ';

                    $editBtn =  '<button ' .
                        ' class="btn btn-outline-success" ' .
                        ' onclick="showData(' . $roles->id . ',true)">Edit' .
                        '</button> ';

                    $deleteBtn =  '<button ' .
                        ' class="btn btn-outline-danger" ' .
                        ' onclick="destroyData(' . $roles->id . ')">Delete' .
                        '</button> ';

                    return $editAccessModule . $editBtn . $deleteBtn;
                })
                ->rawColumns(
                    [
                        'action',
                    ]
                )
                ->make(true);
        }
        return view('/role/role');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        request()->validate([
            'name' => 'required|max:255'
        ]);

        $role = new Role();
        $role->name = $request->name;
        $role->save();
        return response()->json(['status' => "success"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::find($id);

        return response()->json(['status' => "success", 'data' => $role]);
    }


    public function update(Request $request, string $id)
    {
        request()->validate([
            'name' => 'required|max:255'
        ]);

        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();
        return response()->json(['status' => "success"]);
    }

    public function destroy(string $id)
    {
        ModuleWithRole::where('role_id',$id)->delete();
        Role::destroy($id);
        return response()->json(['status' => "success"]);
    }
    public function showRoleAccess(Request $request)
    {
        if (!$request->ajax()) return null;
        $request->validate(
            [
                'id' => 'required|numeric',
            ],
            [
                'id.required' => 'id wajib ada',
            ]
        );

        $targetID = $request->id;

        $roleData = Role::find($targetID);
        $modules = Module::leftjoin('module_with_roles', function ($join) use ($roleData) {
            $join->on('module_with_roles.module_id', '=', 'modules.id')->where('module_with_roles.role_id', '=', $roleData->id);
            //$join->on('module_with_roles.role_id', '=',$roleData->id);
        })->orderBy('modules.ModuleOrder', 'asc')->select('modules.id', 'modules.ModuleName', 'modules.ModuleGroup', 'modules.ModuleSubGroup', DB::raw('CASE WHEN module_with_roles.role_id IS NULL THEN 0 ELSE 1 END AS menu_checked'))->get();
        return response()->json(['status' => "success", 'data' => $modules]);
    }
    public function ManageAccessRole(Request $request)
    {
        //
        request()->validate([
            'role_id' => 'required|numeric'
        ]);
        $status = true;
        $message = "";
        DB::beginTransaction();
        $data=null;
        try {
            $roleData = Role::find($request->role_id);
            if (is_null($roleData)) throw new Exception('Role Not Exist');
            $ModuleAccess = $request->menus ? $request->menus : [];
            foreach ($ModuleAccess as $module) {
                $CekResetEmail = ModuleWithRole::where([
                    'module_id' => (int)$module['id'],
                    'role_id' => $roleData->id,
                ])->first();
                if((int)$module['menu_checked']==1){
                    if (!$CekResetEmail) {
                        $MWR = new ModuleWithRole();
                        $MWR->module_id = (int)$module['id'];
                        $MWR->role_id = $roleData->id;
                        $MWR->save();
                    }
                }else if((int)$module['menu_checked']==0){
                    if ($CekResetEmail) {
                        $CekResetEmail->delete();
                    }
                }
                
            }
            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            $status = false;
            $message = $e->getMessage()."Line No : ". $e->getLine();
        }
        return response()->json(['status' => $status, 'message' => $message,'data'=>$data]);
    }
}
