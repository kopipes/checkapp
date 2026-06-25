<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;

class UserEdit extends Component
{
    public User $user;

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

    public function mount(User $user): void
    {
        $this->user       = $user;
        $this->name       = $user->name;
        $this->email      = $user->email;
        $this->username   = $user->username ?? '';
        $this->role       = $user->role;
        $this->gender     = $user->gender ?? '';
        $this->birth_date = $user->birth_date ? $user->birth_date->format('Y-m-d') : '';
        $this->department = $user->department ?? '';
        $this->employee_id = $user->employee_id ?? '';
        $this->is_active  = $user->is_active;
    }

    protected function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $this->user->id,
            'username'    => 'nullable|string|max:100|unique:users,username,' . $this->user->id,
            'password'    => 'nullable|string|min:6',
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

        $data = [
            'name'        => $this->name,
            'email'       => $this->email,
            'username'    => $this->username ?: null,
            'role'        => $this->role,
            'gender'      => $this->gender ?: null,
            'birth_date'  => $this->birth_date ?: null,
            'department'  => $this->department ?: null,
            'employee_id' => $this->employee_id ?: null,
            'is_active'   => $this->is_active,
        ];

        if ($this->password) {
            $data['password'] = $this->password;
        }

        $this->user->update($data);

        session()->flash('message', 'Data user berhasil diperbarui.');
        $this->redirect(route('admin.users.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.users.edit')
            ->layout('layouts.app-admin');
    }
}
