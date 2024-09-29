<?php

namespace App\Http\Controllers;

use App\Models\ModuleWithRole;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()){
            $roles = Role::query();
            return DataTables::of($roles)
                ->addColumn('action', function ($roles) {
                     
                    $showBtn =  '<button ' .
                                    ' class="btn btn-outline-info" ' .
                                    ' onclick="showProject(' . $roles->id . ')">Show' .
                                '</button> ';
     
                    $editBtn =  '<button ' .
                                    ' class="btn btn-outline-success" ' .
                                    ' onclick="editProject(' . $roles->id . ')">Edit' .
                                '</button> ';
     
                    $deleteBtn =  '<button ' .
                                    ' class="btn btn-outline-danger" ' .
                                    ' onclick="destroyProject(' . $roles->id . ')">Delete' .
                                '</button> ';
     
                    return $showBtn . $editBtn . $deleteBtn;
                })
                ->rawColumns(
                [
                    'action',
                ])
                ->make(true);

        }
        return view('/role/role');
        // if ($request->ajax()) {
        //     $roles = Role::latest()->get();
        //     return DataTables::of($roles)
        //             ->addIndexColumn()
        //             ->addColumn('action', function($row){
   
        //                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct">Edit</a>';
   
        //                    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct">Delete</a>';
    
        //                     return $btn;
        //             })
        //             ->rawColumns(['action'])
        //             ->make(true);
        // }
      
        // return view('productAjax',compact('roles'));
        
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
        
        return response()->json(['status' => "success",'data' => $role]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        request()->validate([
            'name' => 'required|max:255'
        ]);
  
        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();
        return response()->json(['status' => "success"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        Role::destroy($id);
        return response()->json(['status' => "success"]);
    }
}
