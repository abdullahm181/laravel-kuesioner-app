<?php

namespace App\Http\Controllers;

use App\Models\ModuleWithRole;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    function index()
    {
        return view('auth/login');
    }

    function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required',
                'password' => 'required'
            ],
            [
                'email.required' => 'Email wajib diisi',
                'password.required' => 'Password wajib diisi'
            ]
        );

        $infologin = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        if (Auth::attempt($infologin)) {
            // if(Auth::user()->role_id==1){
            //     return redirect('home');
            // }elseif(Auth::user()->role_id==2){
            //     return redirect('home/admin');
            // }
            Session::put('name', Auth::user()->name);
            Session::put('email', Auth::user()->email);
            //$RoleData= Role::findOrFail(Auth::user()->role_id);
            //Session::put('role',$RoleData->name);
            //Session::put('login',TRUE);

            /// put session html sidebar
            $MoluleSideBar = $this->GetSideBarByRole();
            Session::put('sidebarSession', $MoluleSideBar);
            return redirect('/');
        }
        return redirect('')->withErrors('Username dan password yang dimasukkan tidak sesuai')->withInput();
    }
    function registerPage()
    {
        return view('auth/register');
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
        $DefaultRole = Role::where('name', 'user')->first();
        if (!$DefaultRole) {
            return redirect('/register')->withErrors('default role not found');
        }
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>  Hash::make($request->password),
            'isDeleted' => false,
            'isDisable' => false,
            'role_id' => $DefaultRole->id,
        ]);
        return view('auth/register')->with('success', 'Registration successful! Please log in.');
    }

    function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect('');
    }
    function forgotPasswordPage()
    {
        return view('auth/forgotpassword');
    }
    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([

            'email' => 'required|email|exists:users',

        ]);

        $CekResetEmail = DB::table('password_reset_tokens')->where([
            'email' => $request->email
        ])->first();
        if ($CekResetEmail) {

            return redirect('/auth/forgotPasswordPage')->withErrors('You have already send email for forgot password!')->withInput();;
        }


        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([

            'email' => $request->email,

            'token' => $token,

            'created_at' => Carbon::now()

        ]);



        Mail::send('email.forgotpasswordemail', ['token' => $token], function ($message) use ($request) {

            $message->to($request->email);

            $message->subject('Reset Password');
        });



        return  redirect('/')->with('message', 'We have e-mailed your password reset link!');
    }
    function resetPasswordPage($token)
    {
        return view('auth/resetpassword', ['token' => $token]);
    }
    public function submitResetPasswordForm(Request $request)

    {

        $request->validate([

            'email' => 'required|email|exists:users',

            'password' => 'required|string|min:6|confirmed',

            'password_confirmation' => 'required'

        ]);



        $updatePassword = DB::table('password_reset_tokens')

            ->where([

                'email' => $request->email,

                'token' => $request->token

            ])

            ->first();



        if (!$updatePassword) {

            return back()->withInput()->with('error', 'Invalid token!');
        }



        $user = User::where('email', $request->email)

            ->update(['password' => Hash::make($request->password)]);



        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();



        return redirect('/')->with('message', 'Your password has been changed!');
    }

    private function GetSideBarByRole()
    {
        $RoleData = ModuleWithRole::where('role_id', Auth::user()->role_id)->join('modules', 'module_with_roles.module_id', 'modules.id')
            ->select('modules.*')->orderBy('ModuleOrder', 'asc')
            ->get();
        $ModuleGroup = [];
        $MoluleSideBar = '';
        foreach ($RoleData as $c) {
            if (empty($c->ModuleGroup)) {
                $MoluleSideBar = $MoluleSideBar . '<hr class="sidebar-divider my-0">
                <li class="nav-item">
                <a class="nav-link" href="' . url($c->ModuleController . (!empty($c->ModuleAction) ? '/' . $c->ModuleAction : '')) . '">
                    <i class="' . $c->ModuleIcon . '"></i>
                    <span>' . $c->ModuleName . '</span></a>
            </li>';
            } else if (!in_array($c->ModuleGroup, $ModuleGroup)) {
                array_push($ModuleGroup, $c->ModuleGroup);
                $MoluleSideBar = $MoluleSideBar . '
                <hr class="sidebar-divider">
                <div class="sidebar-heading">
                    ' . $c->ModuleGroup . '
                </div>
                ';
                $ModuleInGroups = $RoleData->where('ModuleGroup', $c->ModuleGroup);
                foreach ($ModuleInGroups as $dataGroup) {
                    $ModuleSubGroup = [];
                    $MoluleSideBar = $MoluleSideBar . '<li class="nav-item">
                    <a class="nav-link ' . (empty($dataGroup->ModuleSubGroup) ? '' : 'collapsed') . '" href="' . (empty($dataGroup->ModuleSubGroup) ? url($dataGroup->ModuleController . (!empty($dataGroup->ModuleAction) ? '/' . $dataGroup->ModuleAction : '')) : '#') . '"  ' . (empty($dataGroup->ModuleSubGroup) ? '' : 'data-toggle="collapse" data-target="#' . $dataGroup->ModuleGroup . $dataGroup->ModuleSubGroup . '"
                        aria-expanded="true" aria-controls="collapsePages"') . '>
                        <i class="' . $dataGroup->ModuleIcon . '"></i>
                        <span>' . (empty($dataGroup->ModuleSubGroup) ? $dataGroup->ModuleName : $dataGroup->ModuleSubGroup) . '</span></a>
                    </a>';
                    if (!empty($dataGroup->ModuleSubGroup) && !in_array($dataGroup->ModuleSubGroup, $ModuleSubGroup)) {
                        $MoluleSideBar = $MoluleSideBar . '
                        <div id="' . $dataGroup->ModuleGroup . $dataGroup->ModuleSubGroup . '" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">';
                        $ModuleInSubGroups = $ModuleInGroups->where('ModuleSubGroup', $dataGroup->ModuleSubGroup);
                        foreach ($ModuleInSubGroups as $dataSubGroup) {
                            $MoluleSideBar = $MoluleSideBar . '
                                <a class="collapse-item" href="' . url($dataSubGroup->ModuleController . (!empty($dataSubGroup->ModuleAction) ? '/' . $dataSubGroup->ModuleAction : '')) . '">' . $dataSubGroup->ModuleName . '</a>
                            ';
                        }


                        $MoluleSideBar = $MoluleSideBar . '</div>
                        </div>';
                    }
                    $MoluleSideBar = $MoluleSideBar . '</li> ';
                }
                //$MoluleSideBar=$MoluleSideBar.' <hr class="sidebar-divider d-none d-md-block">';
            }
        }
        return $MoluleSideBar;
    }

    public function profile()
    {
        $dataUser = User::where('users.id',Auth::user()->id)->leftjoin('roles', function ($join){
            $join->on('users.role_id', '=', 'roles.id');
        })->select('users.id','users.name','users.email','users.role_id','users.isDeleted','users.isDisable','roles.name as role_name')->first();

        return view('auth/profile', compact('dataUser'));
    }
    public function storeProfile(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.Auth::user()->id,
        ]);
        $status = true;
        $message = "";
        DB::beginTransaction();
        $data=null;
        try {
            User::findOrFail(Auth::user()->id)->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
   
            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            $status = false;
            $message = $e->getMessage()."Line No : ". $e->getLine();
        }
        return response()->json(['status' => $status, 'message' => $message,'data'=>$data]);
    }
    public function changepassword()
    {
        return view('auth/changepassword');
    }
    public function storeNewPassword(Request $request)
    {
        $request->validate([
            'oldpassword' => 'required|min:6',
            'password' => 'required|min:6|confirmed',
        ]);
        $status = true;
        $message = "";
        DB::beginTransaction();
        $data=null;
        try {
            $currentPasswordStatus = Hash::check($request->oldpassword, auth()->user()->password);if($currentPasswordStatus){

                User::findOrFail(Auth::user()->id)->update([
                    'password' => Hash::make($request->password),
                ]);
    
            }else{
                throw new Exception('Current Password does not match with Old Password');
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
