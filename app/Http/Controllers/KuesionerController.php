<?php

namespace App\Http\Controllers;

use App\Models\Kuesioner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class KuesionerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Kuesioner::query();
            return DataTables::of($roles)
                ->addColumn('action', function ($roles) {

                    $editBtn =  '<button ' .
                        ' class="btn btn-outline-success" ' .
                        ' onclick="showData(' . $roles->id . ',true)">Edit' .
                        '</button> ';

                    $deleteBtn =  '<button ' .
                        ' class="btn btn-outline-danger" ' .
                        ' onclick="destroyData(' . $roles->id . ')">Delete' .
                        '</button> ';

                    return $editBtn . $deleteBtn;
                })
                ->rawColumns(
                    [
                        'action',
                    ]
                )
                ->make(true);
        }
        return view('/kuesioner/kuesioner');
    }
    public function manageKuesioner(Request $request)
    {
        $dataMaster=null;
        return view('/kuesioner/kuesionerManage', compact('dataMaster'));
    }
   
    public function store(Request $request)
    {
        //
    }

  
    public function show(string $id)
    {
        //
    }

 
    public function update(Request $request, string $id)
    {
        //
    }

 
    public function destroy(string $id)
    {
        //
    }
}
