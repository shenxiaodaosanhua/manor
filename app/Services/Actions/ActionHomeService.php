<?php


namespace App\Services\Actions;



use App\Models\User;
use Illuminate\Http\Request;

class ActionHomeService implements ActionService
{
    public function handle(User $user)
    {
        dd($user);
    }
}
