<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\SkillLevel;
use Illuminate\Http\Request;

class SkillLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(SkillLevel::all()->toArray());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!empty($request->get('text'))) {
            $item = new SkillLevel;       
            $item->text = $request->get('text');
            if(!empty($request->get('text_en'))) {
            $item->text_en = $request->get('text_en');
            }
            $item->save();
            return response(['status' => 1], 200);
        }  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SkillLevel  $skillLevel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        if (!empty($request->get('text')) && !empty($request->get('skill_level_id'))) {
            $item = SkillLevel::find($request->get('skill_level_id'));       
            if(!empty($item)) {
                $item->text = $request->get('text');
                if(!empty($request->get('text_en'))) {
                    $item->text_en = $request->get('text_en');
                }
                $item->save();     
                return response(['status' => 1, 'msg' => __('text.ClassifierChanged')], 200);           
            }           
        }    
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SkillLevel  $skillLevel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = SkillLevel::find($id);
        $item->delete();
        return response(['status' => 1], 200);
    }
}
