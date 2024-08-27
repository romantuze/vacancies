<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Language;

class LanguageController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Language::all()->toArray());
    }

    public function store(Request $request) 
    {
        if(!empty($request->get('text'))) {
            $item = new Language;       
            $item->text = $request->get('text');
            if(!empty($request->get('text_en'))) {
                $item->text_en = $request->get('text_en');
            } else {
                $item->text_en = "";
            }
            $item->save();
            return response(['status' => 1], 200);
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Language::find($id);
        $item->delete();
        return response(['status' => 1], 200);
    }


    public function update(Request $request) {
        if (!empty($request->get('text')) && !empty($request->get('language_id'))) {
            $item = Language::find($request->get('language_id'));       
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




}
