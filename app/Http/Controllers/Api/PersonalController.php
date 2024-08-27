<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Personal;
use Illuminate\Http\Request;

class PersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Personal::where('personal_id', NULL)->get()->toArray());
    }

    public function read($lang, $id)
    {
        $items = self::translate_select(Personal::where('personal_id', $id)->get()->toArray(), $lang);        
        return response()->json($items);
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
            $item = new Personal;  
            if(!empty($request->get('personal_id'))) {
                $item->personal_id = (int) $request->get('personal_id');
            }     
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
     * @param  \App\Models\Personal  $personal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        if (!empty($request->get('text')) && !empty($request->get('personal_id'))) {
            $item = Personal::find($request->get('personal_id'));       
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
     * @param  \App\Models\Personal  $personal
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Personal::find($id);
        $item->delete();
        return response(['status' => 1], 200);
    }

    public function translate_select($items_all, $lang) {
        $lang_text = "text";
        if ($lang === "en") {
            $lang_text = "text_en";
        }       
        $items_lang = array();
        foreach ($items_all as $all_item) {
            $item = array();
            $item["id"] = $all_item["id"];  
            $item["text"] = $all_item[$lang_text];       
            array_push($items_lang, $item);    
        }        
        return $items_lang;
    }

}
