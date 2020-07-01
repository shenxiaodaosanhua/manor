<?php


namespace App\Services\Actions;


use App\Models\User;
use Illuminate\Http\Request;

/**
 * Interface ActionService
 * @package App\Services\Actions
 */
interface ActionService
{
    /**
     * @param User $user
     * @return mixed
     */
    public function handle(User $user);
}
