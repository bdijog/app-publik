<?php

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public string $name = '';

    public string $customer_type = 'individual';

    public string $organization_name = '';

    public string $email = '';

    public string $phone = '';

    public string $address = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function register(): mixed
    {
        $this->validate([
            'name' => 'required|string|min:3|max:255',
            'customer_type' => 'required|in:individual,organization,health_facility',
            'organization_name' => $this->customer_type !== 'individual' ? 'required|string|min:3|max:255' : 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email',
            'phone' => 'required|string|min:8|max:20',
            'address' => 'required|string|min:5|max:500',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama lengkap penanggung jawab wajib diisi.',
            'organization_name.required' => 'Nama instansi/fasilitas kesehatan wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar sebagai akun pelanggan.',
            'phone.required' => 'Nomor WhatsApp/Telepon wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $customer = Customer::create([
            'name' => $this->name,
            'customer_type' => $this->customer_type,
            'organization_name' => $this->customer_type !== 'individual' ? $this->organization_name : null,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'password' => $this->password,
        ]);

        Auth::guard('customer')->login($customer);

        session()->flash('success', 'Pendaftaran akun berhasil! Selamat datang di Portal Pelanggan Balai Labkesmas Ambon.');

        return redirect()->route('customer.dashboard');
    }

    public function render(): mixed
    {
        return view('pages::customer.⚡register.register');
    }
};
