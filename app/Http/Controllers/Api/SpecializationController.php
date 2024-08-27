<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Specialization;


class SpecializationController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($lang, $groupSpecializationId)
    {
        $items = self::translate_select(Specialization::where('group_specialization_id', $groupSpecializationId)->get()->toArray(), $lang);
        return response()->json($items);
    }

    public function getSpecializationsAll()
    {
        return response()->json(Specialization::all()->toArray());
    }


    public function store(Request $request) 
    {
        if(!empty($request->get('text'))) {
            $item = new Specialization;
            $item->text = $request->get('text');
            if(!empty($request->get('text_en'))) {
            $item->text_en = $request->get('text_en');
            }
            if(!empty($request->get('group_specialization_id'))) {
            $item->group_specialization_id = (int) $request->get('group_specialization_id');
            }
            $item->save();
            return response(['status' => 1], 200);
        }  
    }


    public function update(Request $request) {
        if (!empty($request->get('text')) && !empty($request->get('specialization_id'))) {
            $item = Specialization::find($request->get('specialization_id'));       
            if(!empty($item)) {
                $item->text = $request->get('text');
                if(!empty($request->get('text_en'))) {
                    $item->text_en = $request->get('text_en');
                }
                $item->save();     
                return response(['status' => 1, 'msg' => 'Специализация обновлена'], 200);           
            }           
        }    
    }

    public function destroy($specializationId) {
        if(!empty($specializationId)) {
            $specialization = Specialization::find($specializationId);        
            if(!empty($specialization)) {
                $specialization = Specialization::destroy($specializationId);
                return response(['status' => 1, 'msg' => 'Специализация удалена'], 200);   
            }         
        }          
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
