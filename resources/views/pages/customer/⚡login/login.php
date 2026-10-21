<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public string $errorMessage = '';

    public function mount(): void
    {
        if (auth('customer')->check()) {
            redirect()->route('customer.dashboard');
        }
    }

    public function fillDemo(): void
    {
        $this->email = 'budi.santoso@example.com';
        $this->password = 'password';
    }

    public function login(): mixed
    {
        $this->errorMessage = '';

        $this->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (Auth::guard('customer')->attempt([
            'email' => $this->email,
            'password' => $this->password,
        ], $this->remember)) {
            session()->regenerate();

            return redirect()->intended(route('customer.dashboard'));
        }

        $this->errorMessage = 'Kombinasi alamat email atau kata sandi tidak cocok.';

        return null;
    }

    public function render(): mixed
    {
        return view('pages::customer.⚡login.login');
    }
};
