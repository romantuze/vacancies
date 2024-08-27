<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Work;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\CompanyController;

class DepositController extends Controller
{

    public function create($user_id)
    {
        $user_id = (int) $user_id;
    	return view('deposit.create',[
            "user_id" => $user_id,
        ]);
    }

    public function store(Request $request) {
        if (!empty($request->get('amount')) && !empty($request->get('user_id'))) {
            $user_id = (int) $request->get('user_id');
            $amount = intval($request->get('amount'));  
            $doc_num = $request->get('doc_num');
            $doc_date =  $request->get('doc_date');
            $doc_amount = $request->get('doc_amount');
            $user = User::find($user_id);
            if (!empty($user) && $amount > 0) {
                $user->balance = intval($user->balance) + $amount;              
                $user->doc_num = $doc_num;
                $user->doc_date = $doc_date;
                $user->doc_amount = $doc_amount;
                $user->save();
                CompanyController::countCompanyUserInfo($user_id);
                return response(['status' => 1], 200);
            }         
        }
        return response(['status' => 0], 200);         
    }
    
}
