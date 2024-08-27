<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    use HasFactory;

    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'text',
        'text_en',
        'group_specialization_id',
    ];




    public static function implode_specializations($specializations_request)
    {
        if (!empty($specializations_request) && !empty($specializations_request[0])) {
            $specializations_array =  [];  
            foreach ($specializations_request as $array_key => $array_value) {
                $array_item = $specializations_request[$array_key]['text'];          
                if (!empty($array_item)) { array_push($specializations_array, $array_item); }
            }
            $specializations=implode("|",$specializations_array);
        }
        else { $specializations = ''; }
        return $specializations;
    }


    public function implode_questionaire_specializations($specializations_request)
    {
        if (!empty($specializations_request) && !empty($specializations_request[0])) {
            $specializations_array =  [];  
            foreach ($specializations_request as $array_key => $array_value) {
                $array_item = $array_value->text;       
                if (!empty($array_item)) { array_push($specializations_array, $array_item); }
            }
            $specializations=implode("|",$specializations_array);
        }
        else { $specializations = ''; }
        return $specializations;
    }

    public function explode_specializations($specializations_string) {
        $specializations_array = explode("|", $specializations_string);
        /*$specializations = [];
        foreach ($specializations_array as $specialization) {
            $specialization_id = (int) $specialization;
            if($specialization_id> 0) {
                $item_specialization = Specialization::find($specialization_id);
                $text_specialization = $item_specialization->text; 
                if(!empty($text_specialization)) {
                    array_push($specializations,$text_specialization);    
                }                
            }
        }*/        
        return $specializations_array;
    }


    public function explode_edit_specializations($specializations_string) {

          $specializations_array = explode("|", $specializations_string);
            $specializations = [];

            $list_group_specializations = GroupSpecialization::all();
            $list_group_specializations_new = [];

            foreach ($list_group_specializations as $list_group_specializations_value) {
             $list_group_specializations_item = [];
             $list_group_specializations_item["id"] = $list_group_specializations_value->id;
             $list_group_specializations_item["text"] = $list_group_specializations_value->text;
             array_push($list_group_specializations_new, $list_group_specializations_item);
            }

            foreach ($specializations_array as $specialization_key => $specialization_value) {

              /*$specialization_id = (int) $specialization_value;
              $item_specialization = Specialization::find($specialization_id);*/

              $item_specialization = Specialization::where("text",$specialization_value)->first();

              if (!empty($item_specialization)) {

                $group_specialization_id = $item_specialization->group_specialization_id;
                
                $list_specializations = Specialization::where('group_specialization_id',$group_specialization_id)->get();
                $list_specializations_new = [];

                foreach ($list_specializations as $list_specializations_value) {

                 /*$list_specializations_item = [];
                 $list_specializations_item["id"] = $list_specializations_value->id;
                 $list_specializations_item["text"] = $list_specializations_value->text;
                 array_push($list_specializations_new, $list_specializations_item);*/

                 if (!empty($list_specializations_value->text)) {
                     array_push($list_specializations_new, $list_specializations_value->text);
                 } 
                }

                $specizalization_new = [];
                $specizalization_new["id"] = $specialization_key;
                $specizalization_new["list_group_specializations"] = $list_group_specializations_new;
                $specizalization_new["list_specializations"] = $list_specializations_new;
                $specizalization_new["text"] = $specialization_value;
                $specizalization_new["group"] = $group_specialization_id;
                 array_push($specializations,$specizalization_new);    
                 $list_specializations_new = [];
                 $specizalization_new = [];

              }
        
            }

            return $specializations;
    }

}
