<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;

class UserCreate extends Component
{
    public string $name        = '';
    public string $email       = '';
    public string $username    = '';
    public string $password    = '';
    public string $role        = 'user';
    public string $gender      = '';
    public string $birth_date  = '';
    public string $department  = '';
    public string $employee_id = '';
    public bool   $is_active   = true;

    protected function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'username'    => 'nullable|string|max:100|unique:users,username',
            'password'    => 'required|string|min:6',
            'role'        => 'required|in:admin,user',
            'gender'      => 'nullable|in:male,female',
            'birth_date'  => 'nullable|date',
            'department'  => 'nullable|string|max:100',
            'employee_id' => 'nullable|string|max:50',
            'is_active'   => 'boolean',
        ];
    }

    public function save(): void
    {
        $this->validate();

        User::create([
            'name'        => $this->name,
            'email'       => $this->email,
            'username'    => $this->username ?: null,
            'password'    => $this->password,
            'role'        => $this->role,
            'gender'      => $this->gender ?: null,
            'birth_date'  => $this->birth_date ?: null,
            'department'  => $this->department ?: null,
            'employee_id' => $this->employee_id ?: null,
            'is_active'   => $this->is_active,
        ]);

        session()->flash('message', 'User berhasil ditambahkan.');
        $this->redirect(route('admin.users.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.users.create')
            ->layout('layouts.app-admin');
    }
}
