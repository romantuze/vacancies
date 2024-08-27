<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FileUpload extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
    	'user_id',
        'name',
        'path'
    ];   

    public function get_photo_url($photo_name, $opened)
    {
    	$photo_response = null;
        if (!empty($photo_name)) {
             $photo = $photo_name;
             $photo_url = "uploads/photo/".$photo;
             $photo_file = Storage::url($photo_url);
             if (!empty($photo_file) && $opened) {               
                 $photo_response = $photo_file;
             }             
        } 
        return $photo_response;
    } 

    public function get_resume_url($resume_name, $opened)
    {
    	$resume_response = null;
        if (!empty($resume_name)) {
             $resume = $resume_name;
             $resume_url = "uploads/resume/".$resume;
             $resume_file = Storage::url($resume_url);
             if (!empty($resume_file) && $opened) {               
                 $resume_response = $resume_file;
             }             
        } 
        return $resume_response;        
    } 

}