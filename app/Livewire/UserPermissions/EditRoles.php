<?php

namespace App\Livewire\UserPermissions;

use App\Models\User;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class EditRoles extends Component
{
    use WithPagination;

    #[Locked]
    public User $user;

    #[Locked]
    public array $currentRoles = [];

    public $search;

    public function mount($roles, $user)
    {
        $this->user = $user;
        foreach($roles as $role){
            $this->currentRoles[$role->id] = $role->name;
        }
    }

    public function render()
    {
        return view('livewire..user-permissions.edit-roles', [
            'roles' => $this->queryRoles()
        ]);
    }

    private function queryRoles()
    {
        $search = $this->validateSearch();
        if(is_null($search)){
            $roles = Role::paginate(5);
        } else {
            $roles = Role::whereRaw("
                `name` LIKE ?
            ", ["%$search%"])->paginate(5);
            $this->resetPage();
        }
        return $roles;
    }

    private function validateSearch(): string|null
    {
        $validated = null;
        if(is_string($this->search)){
            if(
                mb_strlen($this->search) <= 255
                && mb_strlen($this->search) >= 2
            ){
                $validated = $this->search;
            }
        }
        return $validated;
    }

    private function validateId($id)
    {
        $validated = null;
        if(is_integer($id)){
            if($id > 0){
                $validated = $id;
            }
        }
        return $validated;
    }

    public function removeRole($roleId, $userId)
    {
        $role = Role::find($this->validateId($roleId));
        $user = User::find($this->validateId($userId));
        if(!is_null($role) && !is_null($user)){
            $user->removeRole($role->name);
        }
        $this->reset('currentRoles');
        foreach($user->roles as $currentRole){
            $this->currentRoles[$currentRole->id] = $currentRole->name;
        }
    }

    public function addRole($roleId, $userId)
    {
        $role = Role::find($this->validateId($roleId));
        $user = User::find($this->validateId($userId));
        if(!is_null($role) && !is_null($user)){
            $user->assignRole($role->name);
        }
        $this->reset('currentRoles');
        foreach($user->roles as $currentRole){
            $this->currentRoles[$currentRole->id] = $currentRole->name;
        }
    }
}
