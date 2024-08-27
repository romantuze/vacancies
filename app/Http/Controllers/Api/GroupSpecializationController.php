<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GroupSpecialization;

class GroupSpecializationController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(GroupSpecialization::all()->toArray());
    }

    public function store(Request $request) 
    {
        if(!empty($request->get('text'))) {
            $item = new GroupSpecialization;       
            $item->text = $request->get('text');
            if(!empty($request->get('text_en'))) {
            $item->text_en = $request->get('text_en');
            }
            $item->save();
            return response(['status' => 1], 200);
        }  
    }

}
