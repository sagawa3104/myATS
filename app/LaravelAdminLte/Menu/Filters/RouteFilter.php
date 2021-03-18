<?php

namespace App\LaravelAdminLte\Menu\Filters;

use Illuminate\Support\Facades\Auth;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;

class RouteFilter implements FilterInterface
{
    public function transform($item)
    {
        if(isset($item['preroute'])){
            $preroute = $item['preroute'];
            $user_id = Auth::user()->id;

            $item['route'] = [$preroute, ['user' => $user_id ]];
        }
        return $item;
    }
}