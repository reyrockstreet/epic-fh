<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\ExperienceCertificate;
use App\Models\GenerateOfferLetter;
use App\Models\JoiningLetter;
use App\Models\NOC;
use App\Models\User;
use App\Models\Utility;
use Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */

     
       public function __construct()
    {
        $this->middleware('guest');
    }
      

    public function create()
    {
        // return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        //ReCpatcha
        if(env('RECAPTCHA_MODULE') == 'on')
        {
            $validation['g-recaptcha-response'] = 'required|captcha';
        }else{
            $validation = [];
        }

        $this->validate($request, $validation);
        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string',
                         'min:8','confirmed', Rules\Password::defaults()],
        ]);

        $fileName   = time() . "_" . $request->ktp->getClientOriginalName();

        $user = User::create([
            'name' => $request->name,
            'whatsapp' => $request->whatsapp,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'ktp' => $fileName,
            'type' => 'Farmer',
            //'default_pipeline' => 1,
            //'plan' => 1,
            'lang' => Utility::getValByName('default_language'),
            'avatar' => '',
            'created_by' => '2', //EPIC FH Company
        ]);

        if(!empty($request->ktp))
            {
                    $dir        = 'uploads/ktp';
                    $url        = '';
                    $path       = Utility::upload_file($request, 'ktp', $fileName, $dir, []);
                    if ($path['flag'] == 0) {
                        return redirect()->back()->with('status', __($path['msg']));
                    }
            }
        
        //\App\Models\Utility::employeeDetails($user->id,\Auth::user()->creatorId());
        Auth::login($user);

        $settings = Utility::settings();

        if ($settings['email_verification'] == 'on') {
            try {
                event(new Registered($user));


                $role_r = Role::findByName('company');
                $user->assignRole($role_r);
                $user->userDefaultDataRegister($user->id);
                $user->userWarehouseRegister($user->id);

                //default bank account for new company
                $user->userDefaultBankAccount($user->id);

                Utility::chartOfAccountTypeData($user->id);
                Utility::chartOfAccountData($user);
                // default chart of account for new company
                Utility::chartOfAccountData1($user->id);

                Utility::pipeline_lead_deal_Stage($user->id);
                Utility::project_task_stages($user->id);
                Utility::labels($user->id);
                Utility::sources($user->id);
                Utility::jobStage($user->id);
                GenerateOfferLetter::defaultOfferLetterRegister($user->id);
                ExperienceCertificate::defaultExpCertificatRegister($user->id);
                JoiningLetter::defaultJoiningLetterRegister($user->id);
                NOC::defaultNocCertificateRegister($user->id);
            } catch (\Exception $e) {

                $user->delete();
                //return $e;
                return redirect('/register/lang?')->with('status', __('Email SMTP settings does not configure so please contact to your site admin.'));
            }
            return view('auth.verify');
        } else {
            $user->email_verified_at = date('h:i:s');
            $user->save();
            $role_r = Role::findByName('company');
            $user->assignRole($role_r);
            $user->userDefaultData($user->id);
            $user->userDefaultDataRegister($user->id);
            GenerateOfferLetter::defaultOfferLetterRegister($user->id);
            ExperienceCertificate::defaultExpCertificatRegister($user->id);
            JoiningLetter::defaultJoiningLetterRegister($user->id);
            NOC::defaultNocCertificateRegister($user->id);
            return redirect(RouteServiceProvider::HOME);
        }

    }

    public function showRegistrationForm($lang = '')
    {

        $settings = Utility::settings();
//    $lang = $settings['default_language'];

        if($settings['enable_signup'] == 'on')
        {
            if($lang == '')
            {
                $lang = Utility::getValByName('default_language');
            }
            \App::setLocale($lang);

            $roles = Role::where('created_by', '2')->get()->pluck('name', 'id');
            $roles->prepend('All', '0');

            return view('auth.register', compact('lang', 'roles'));
        }
        else
        {
            return \Redirect::to('login');
        }
    }

}
