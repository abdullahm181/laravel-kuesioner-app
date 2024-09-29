<?php

namespace App\Http\Middleware;

use App\Models\ModuleWithRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Constraint\Count;
use Symfony\Component\HttpFoundation\Response;

class UserAccessFilter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$underIndex=null): Response
    {
        if (!Auth::check()) return redirect('login');
        $user = Auth::user();
        // if(auth()->user()->role_id==$role){
        //     return $next($request);
        // }
        $request_url = $request->path();

        $route=$request->route();
        $get_class_basename=class_basename($route->getAction()['controller']);
        $listBaseName=explode('@', $get_class_basename);
        $Conroller=Count($listBaseName)>0?$listBaseName[0]:null;
        $Action=Count($listBaseName)>1?$listBaseName[1]:null;

        $CheckModuleRole= ModuleWithRole::where('role_id',Auth::user()->role_id)->join('modules', 'module_with_roles.module_id', 'modules.id')
            ->select('modules.*')->whereRaw("upper(CONCAT(modules.".'"ModuleController"'.",'CONTROLLER')) = '".strtoupper($Conroller)."'")->whereRaw("upper(modules.".'"ModuleAction"'.") = '".strtoupper((empty($underIndex)?$Action:$underIndex))."'")->first();
        //     ->whereRaw([
        //         'ModuleAction' => $Action,
        //         'ModuleGroup' => $Conroller
        //  ])
        //  ->first();

        // $dataFeedback=[
        //     'message'=> 'anda tidak diperbolehkan akses halaman ini.',
        //     'Conroller'=>$Conroller,
        //     'Action'=>$Action,
        //     'Route'=>$route,
        //     'CheckModuleRole'=>$CheckModuleRole
        // ];

        //return response()->json($dataFeedback);

        if(!$CheckModuleRole){
            abort(401, 'This action is unauthorized.');
        }
       
        return $next($request);
    }
}
