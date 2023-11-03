<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Filament\Http\Middleware\Authenticate as FilamentAuthenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AutoAuthenticate extends FilamentAuthenticate
{
    protected function authenticate($request, array $guards): void
    {
        if (!Auth::check()) {
            $user = User::query()
                ->where('email', 'admin@gotipath.com')
                ->first();
            if (!$user) {
                $user = User::create([
                    'name' => 'Super Admin',
                    'email' => 'admin@gotipath.com',
                    'password' => bcrypt('password'),
                ]);
            }
            Auth::login($user, true);
        }
        parent::authenticate($request, $guards);
    }
}
