<?php

namespace App\Gates;

use App\Models\User;

class AdminMenuGate
{
    public function showAdminMenu(User $user){

        

        return (bool)$user->is_admin;
    }
}