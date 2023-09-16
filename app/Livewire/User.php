<?php

namespace App\Livewire;

use App\Models\User as ModelsUser;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class User extends Component
{
    public $name;
    public $email;
    public $password;

    public function store()
    {
        // Validasi input
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // Simpan data ke dalam database
        ModelsUser::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        // Reset input fields
        $this->reset(['name', 'email', 'password']);

        // Tampilkan pesan sukses
        session()->flash('message', 'Data berhasil disimpan.');
    }

    public function render()
    {
        $users = ModelsUser::orderBy('id', 'DESC')->get();
        
        return view('livewire.user', compact('users'))
            ->layout('component.app');
    }
}
