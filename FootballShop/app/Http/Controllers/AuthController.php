<?php
    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;
    use App\Models\User;



    class AuthController extends Controller
    {
        // Regisztrációs metódus
        public function register(Request $request)
        {
            $request->validate([
                'email' => 'required|email|unique:users',
                'name' => 'required|string|max:255',
                'password' => 'required|string|min:6|confirmed',
            ]);

            User::create([
                'email' => $request->email,
                'name' => $request->name,
                'password' => Hash::make($request->password),
            ]);

            return redirect('/login')->with('success', 'Sikeres regisztráció! Most jelentkezz be.');
        }


        // Bejelentkezés

        public function login(Request $request)
        {
            // Ellenőrizd, hogy milyen adatok érkeznek be
            dd($request->all());

            // Validáció
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Keresd meg az email cím alapján a felhasználót
            $user = User::where('email', $request->email)->first();

            // Ellenőrizd, hogy létezik-e a felhasználó és helyes-e a jelszó
            if ($user && Hash::check($request->password, $user->password)) {
                Auth::login($user);
                return redirect()->intended('/dashboard');
            } else {
                return back()->withErrors([
                    'email' => 'A megadott email-cím és jelszó nem egyeznek.',
                ])->withInput();
            }
        }


        // Automatikus bejelentkezés (Remember Me)
        public function autoLogin()
        {
            if (Auth::check()) {
                return redirect('/dashboard');
            }

            return redirect('/login');
        }

        // Kijelentkezés
        public function logout(Request $request)
        {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login');
        }
    }
