<?php

namespace App\Http\Middleware;

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
    public function handle(Request $request, Closure $next,$role=null): Response
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
        $Conroller=Count($listBaseName)>0?$listBaseName[0]:"";
        $Action=Count($listBaseName)>1?$listBaseName[1]:"";
        $dataFeedback=[
            'message'=> 'anda tidak diperbolehkan akses halaman ini.',
            'Conroller'=>$Conroller,
            'Action'=>$Action,
        ];
        //abort(401, 'This action is unauthorized.');
        //return response()->json($dataFeedback);
        return $next($request);
    }
}
