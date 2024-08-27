<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Degree;
use Illuminate\Http\Request;

class DegreeController extends Controller
{

    public function index($lang)
    {
        $degrees = self::translate_select(Degree::all()->toArray(), $lang); 
        return response()->json($degrees);
    }

    public function store(Request $request)
    {
        if(!empty($request->get('text'))) {
            $item = new Degree;       
            $item->text = $request->get('text');
            if(!empty($request->get('text_en'))) {
            $item->text_en = $request->get('text_en');
            }
            $item->save();
            return response(['status' => 1], 200);
        }  
    }


    public function update(Request $request, Degree $degree)
    {
        
    }

    public function destroy($id)
    {
        $item = Degree::find($id);
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
