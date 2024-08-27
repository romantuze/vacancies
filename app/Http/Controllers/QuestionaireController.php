<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Work;
use App\Models\User;
use App\Models\Questionaire;
use Illuminate\Http\Request;
use Auth;

class QuestionaireController extends Controller
{

    public function create(Request $request, $slug)
    {

      $work = Work::where('slug',$slug)->first();

      if (empty($work)) {
          abort(404);
          return false;
      }

      $workId =  $work->id;

      $lang = config('app.locale');
      $user = User::find(1);        
      if (!empty($user) && !empty($user->lang)) {
        $lang = $user->lang;
      }

      $date_close = $work->date_close;
      $closed_date = date('d.m.Y h:i',strtotime($date_close));

      $current_date = date('Y-m-d h:i:s');

      $closed = false;

      if ((!empty($date_close) && $current_date > $date_close) || ($work->status === 3)) {
        $closed = true;        
      }       
      
      $refer = '';
      if (!empty($request->k)) {
        $refer = $request->k;
      }         

      return view('questionaire.create',[
            'work_id' => $workId,  
            'slug' => $slug,
            'closed' => $closed,
            'closed_date' => $closed_date,
            'refer' => $refer,
            'lang' => $lang,
      ]);

    }

    public function destroy($itemId)
    {
      if ($itemId > 0) {
        $questionaire = Questionaire::destroy($itemId);      
        return "Опросник удален";    
      }       
      abort(404);
      return false;
    }

       
}
