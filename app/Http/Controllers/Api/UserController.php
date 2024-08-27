<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function show($userId)
    {        
    	$userId = (int) $userId;
    	$user = User::find($userId);
    	if (!empty($user)) {
    		if (!empty($user->logo)) {

            $logo = User::get_logo($user->logo);   

            if (!empty($logo)) {
             	$company_logo = $logo;
             	$user->logo = $company_logo;
         	}
         	}

            $user->balance = intval($user->balance);

    		return response()->json($user->toArray());	
    	}        
    }       

}
