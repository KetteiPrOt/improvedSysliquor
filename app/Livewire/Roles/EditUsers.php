<?php

namespace App\Livewire\Roles;

use App\Models\User;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class EditUsers extends Component
{
    use WithPagination;
    use WithPagination;

    #[Locked]
    public Role $role;

    #[Locked]
    public array $currentUsers = [];

    public $search;

    public function mount($users, $role)
    {
        $this->role = $role;
        foreach($users as $user){
            $this->currentUsers[$user->id] = $user->name;
        }
    }

    public function render()
    {
        return view('livewire..roles.edit-users', [
            'users' => $this->queryUsers()
        ]);
    }

    private function queryUsers()
    {
        $search = $this->validateSearch();
        if(is_null($search)){
            $users = 
                User::whereNotIn(
                        'id',
                        array_flip($this->currentUsers)
                    )->paginate(5);
        } else {
            $users = 
                User::whereRaw("
                        `name` LIKE ?
                    ", ["%$search%"])
                    ->whereNotIn(
                        'id',
                        array_flip($this->currentUsers)
                    )
                    ->paginate(5);
            $this->resetPage();
        }
        return $users;
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

    public function removeUser($userId, $roleId)
    {
        $user = User::find($this->validateId($userId));
        $role = Role::find($this->validateId($roleId));
        if(!is_null($role) && !is_null($user)){
            $user->removeRole($role->name);
        }
        $this->reset('currentUsers');
        foreach($role->users as $currentUser){
            $this->currentUsers[$currentUser->id] = $currentUser->name;
        }
    }

    public function addUser($userId, $roleId)
    {
        $user = User::find($this->validateId($userId));
        $role = Role::find($this->validateId($roleId));
        if(!is_null($role) && !is_null($user)){
            $user->assignRole($role->name);
        }
        $this->reset('currentUsers');
        foreach($role->users as $currentUser){
            $this->currentUsers[$currentUser->id] = $currentUser->name;
        }
    }
}
