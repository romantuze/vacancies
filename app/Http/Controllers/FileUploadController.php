<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FileUpload;
use App\Models\User;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
   public function upload(Request $request){
      $user_id = (int) $request->user_id; 
      $request->validate([
         'file' => 'required|mimes:jpg,jpeg,png|max:2048'
      ]);    
      $fileUpload = new FileUpload;    
      if($request->file()) {
         $file_name = $user_id.'_'.time().'.'.$request->file->getClientOriginalExtension();
         $thumb_file_name = 'thumb_'.$file_name;
         $file_path = $request->file('file')->storeAs('uploads/logo', $file_name, 'public');
         $thumbnail = Image::make(Storage::path('/public/uploads/logo/').$file_name);
         $fileUpload->user_id = $user_id;
         $fileUpload->name = $file_name;
         $fileUpload->path = $file_path;
         $fileUpload->save();
         $user = User::find($user_id);
         if (!empty($user)) {
            $user->logo = $file_name;
            $user->save();
         }
         return response()->json(['success'=>'File uploaded successfully.']);
      }
   }


   public function upload_resume(Request $request) {
      $user_id = 1;       

      $request->validate([
         'file' => 'required|mimes:jpg,jpeg,png,pdf,doc,docx|max:4096',
      ]);

      $fileUpload = new FileUpload;

      if($request->file()) {
            $file_resume_name = time().'.'.$request->resume->getClientOriginalExtension();
            $file_resume_path = $request->file('resume')->storeAs('uploads/resume', $file_resume_name, 'public');
            if ($file_resume_path) {
            $fileUpload->user_id = $user_id;
            $fileUpload->name = $file_resume_name;
            $fileUpload->path = $file_resume_path;
            $fileUpload->save();                

            return response()->json(['success'=>'File resume uploaded successfully.', 'file_name' => $file_resume_name]);
            }
      }
   }

   public function upload_photo(Request $request) {
      $user_id = 1;       

      $request->validate([
         'file' => 'required|mimes:jpg,jpeg,bmp,png|max:2048'
      ]);

      $fileUpload = new FileUpload;

      if($request->file()) {
            $file_photo_name = time().'.'.$request->resume->getClientOriginalExtension();
            $file_photo_path = $request->file('resume')->storeAs('uploads/resume', $file_photo_name, 'public');
            if ($file_photo_path) {
            $fileUpload->user_id = $user_id;
            $fileUpload->name = $file_photo_name;
            $fileUpload->path = $file_photo_path;
            $fileUpload->save();                 

            return response()->json(['success'=>'File photo uploaded successfully.', 'file_name' => $file_photo_name]);
            }
      }
   }

}
