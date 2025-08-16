<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ParentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/school-dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            $user = Auth::user();
            
            // Check if user is active
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Account is deactivated. Please contact administrator.']);
            }

            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return Auth::attempt($request->only('email', 'password'), $request->boolean('remember'));
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        return redirect()->intended($this->redirectTo);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = $this->create($request->all());
        
        // Create role-specific record
        $this->createRoleSpecificRecord($user, $request->all());

        Auth::login($user);

        return redirect($this->redirectTo);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,teacher,student,parent'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female,other'],
            
            // Role-specific validation
            'student_id' => ['required_if:role,student', 'string', 'unique:students,student_id'],
            'class_id' => ['required_if:role,student', 'exists:classes,id'],
            'admission_date' => ['required_if:role,student', 'date'],
            'guardian_name' => ['required_if:role,student', 'string'],
            'guardian_phone' => ['required_if:role,student', 'string'],
            
            'employee_id' => ['required_if:role,teacher', 'string', 'unique:teachers,employee_id'],
            'qualification' => ['required_if:role,teacher', 'string'],
            'specialization' => ['required_if:role,teacher', 'string'],
            'joining_date' => ['required_if:role,teacher', 'date'],
            
            'occupation' => ['required_if:role,parent', 'string'],
            'workplace' => ['nullable', 'string'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => $data['gender'] ?? null,
            'is_active' => true,
        ]);
    }

    /**
     * Create role-specific record after user creation
     *
     * @param User $user
     * @param array $data
     * @return void
     */
    protected function createRoleSpecificRecord(User $user, array $data)
    {
        switch ($user->role) {
            case 'student':
                Student::create([
                    'user_id' => $user->id,
                    'student_id' => $data['student_id'],
                    'class_id' => $data['class_id'],
                    'admission_date' => $data['admission_date'],
                    'roll_number' => $data['roll_number'] ?? null,
                    'guardian_name' => $data['guardian_name'],
                    'guardian_phone' => $data['guardian_phone'],
                    'guardian_email' => $data['guardian_email'] ?? null,
                    'guardian_address' => $data['guardian_address'] ?? null,
                    'is_active' => true,
                ]);
                break;

            case 'teacher':
                Teacher::create([
                    'user_id' => $user->id,
                    'employee_id' => $data['employee_id'],
                    'qualification' => $data['qualification'],
                    'specialization' => $data['specialization'],
                    'joining_date' => $data['joining_date'],
                    'experience' => $data['experience'] ?? 0,
                    'is_active' => true,
                ]);
                break;

            case 'parent':
                ParentModel::create([
                    'user_id' => $user->id,
                    'occupation' => $data['occupation'],
                    'workplace' => $data['workplace'] ?? null,
                    'income_range' => $data['income_range'] ?? null,
                    'is_active' => true,
                ]);
                break;
        }
    }

    /**
     * Show user profile
     *
     * @return \Illuminate\View\View
     */
    public function showProfile()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    /**
     * Update user profile
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->update($request->only(['name', 'phone', 'address', 'date_of_birth', 'gender']));

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Show change password form
     *
     * @return \Illuminate\View\View
     */
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    /**
     * Change user password
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password changed successfully!');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
