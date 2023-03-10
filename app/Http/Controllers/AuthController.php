<?php
  
namespace App\Http\Controllers\Auth;
  
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

  
class AuthController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        return view("halaman.signIn");
    }  
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registration()
    {
        return view("halaman.signUp");
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function resetpassword()
    {
        return view("halaman.resetpass");
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postLogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            //dd($request->username);
            
            return redirect()->intended('dashboard')
                        ->withSuccess('Login Sukses!');
        }
  
        return redirect("login")->withSuccess('Anda gagal Login, Coba lagi!');
    }
      
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postRegistration(Request $request)
    {  
        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
           
        $data = $request->all();
        $check = $this->create($data);
         
        return redirect("signIn")->withSuccess('Silahkan Login!');
    }
    

/**
     * Write code on Method
     *
     * @return response()
     */
    public function postresetpassword(Request $request)
    {  
        $request->validate([
            '_token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        $groupasset = User::where('email',$request->email);
        $updatedata = $groupasset->update(['email' => $request->email, 'password' => Hash::make($request->password)]);
        //Zdd();
        if ($updatedata > 0) {
            return redirect()->route('signIn')->with('status', 'Password Berhasil Diubah');
        }
        else {
            back()->withErrors(['email' => ['Terjadi Kesalahan']]);
        }
        // $status = Password::reset(
        //     $request->only('password', 'password_confirmation'),
        //     function ($user, $password) {
        //         dd('woi');
        //         dd($password);
        //         $user->forceFill([
        //             'password' => Hash::make($password)
        //         ])->setRememberToken(Str::random(60));
                    
        //         $user->save();
     
        //         event(new PasswordReset($user));
        //     }
            
        // );
    
        // return $status === Password::PASSWORD_RESET
        //         ? redirect()->route('signIn')->with('status', __($status))
        //         : back()->withErrors(['email' => [__($status)]]);

        
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function dashboard()
    {
        if(Auth::check()){
            return view('dashboard');
        }
  
        return redirect("login")->withSuccess('Anda gagal Login, Coba lagi!');
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function create(array $data)
    {
      return User::create([
        'username' => $data['username'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }   
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout() {
        Session::flush();
        Auth::logout();
  
        return Redirect('login')->withSuccess('Anda telah Logout!');
    }
}