<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use Illuminate\Support\Facades\DB;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Module::query()->orderBy('ModuleOrder', 'asc');
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

                    return $showBtn . $editBtn;
                })
                ->rawColumns(
                    [
                        'action',
                    ]
                )
                ->make(true);
        }
        return view('/module/module');
    }

    public function show(string $id)
    {
        $role = Module::find($id);

        return response()->json(['status' => "success", 'data' => $role]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'ModuleName' => 'required',
            'ModuleOrder' => 'required|numeric',
            'ModuleController' => 'required',
        ]);
        $status = true;
        $message = "";
        DB::beginTransaction();
        $data=null;
        try {       
            $User = Module::find($id);
            $User->ModuleName = $request->ModuleName;
            $User->ModuleGroup = $request->ModuleGroup;
            $User->ModuleSubGroup = $request->ModuleSubGroup;
            $User->ModuleOrder = $request->ModuleOrder;
            $User->ModuleIcon = $request->ModuleIcon;
            $User->save();
            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            $status = false;
            $message = $e->getMessage()."Line No : ". $e->getLine();
        }
        return response()->json(['status' => $status, 'message' => $message,'data'=>$data]);
    }

}
