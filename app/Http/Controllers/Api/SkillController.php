<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Skill;

class SkillController extends Controller
{

    public function index($lang)
    {

        $items_all = Skill::whereNull('skill_id')->with('childrenSkills')->get()->toArray();
        $lang_text = "text";
        if ($lang === "en") {
            $lang_text = "text_en";
        } 
        $items_lang = array();
        foreach ($items_all as $all_item) {
            $item = array();
            $item["id"] = $all_item["id"];  
            $item["text"] = $all_item[$lang_text];
            $children_skills = $all_item["children_skills"];
            $children_skills_lang = array();
            foreach ($children_skills as $children_skills_item) {
                $children_item = array();
                $children_item["id"] = $children_skills_item["id"];  
                $children_item["text"] = $children_skills_item[$lang_text];
                $skills = $children_skills_item["skills"];
                $skills_lang = array();
                foreach ($skills as $skills_item) {
                    $skill_item = array();
                    $skill_item["id"] = $skills_item["id"];  
                    $skill_item["text"] = $skills_item[$lang_text];
                    array_push($skills_lang, $skill_item);    
                }
                $children_item["skills"] = $skills_lang;
                array_push($children_skills_lang, $children_item);    
            }
            $item["children_skills"] = $children_skills_lang;
            array_push($items_lang, $item);    
        }   


        return response()->json($items_lang);
    }    

    public function read($id)
    {
        $items_skills = Skill::where('skill_id', $id)->get()->toArray();
        $lang = "en";
        $lang_text = "text";
        if ($lang === "en") {
            $lang_text = "text_en";
        }       
        $items_lang = array();
        foreach ($items_skills as $all_item) {
            $item = array();
            $item["id"] = $all_item["id"];  
            $item["text"] = $all_item[$lang_text];       
            array_push($items_lang, $item);    
        }        
        return $items_lang;        
    }   


    public function store(Request $request) 
    {
        if(!empty($request->get('text'))) {
            $skill = new Skill;
            if(!empty($request->get('skill_id'))) {
            $skill->skill_id = (int) $request->get('skill_id');
            }
            $skill->text = $request->get('text');
            $skill->save();
            return response(['status' => 1, 'msg' => 'Навык добавлен'], 200);
        }  
    }

    public function update(Request $request) {
        if (!empty($request->get('text')) && !empty($request->get('skill_id'))) {
            $item = Skill::find($request->get('skill_id'));       
            if(!empty($item)) {
                $item->text = $request->get('text');
                if(!empty($request->get('text_en'))) {
                    $item->text_en = $request->get('text_en');
                }
                $item->save();     
                return response(['status' => 1, 'msg' => 'Навык обновлен'], 200);           
            }           
        }    
    }

    public function destroy($skillId) {
        if(!empty($skillId)) {
            $skill = Skill::find($skillId);        
            if(!empty($skill)) {
                $skill = Skill::destroy($skillId);
                return response(['status' => 1, 'msg' => 'Навык удален'], 200);   
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
