<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Facilitation;
use Illuminate\Http\Request;

class FacilitationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Facilitation::all()->toArray());
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
            $item = new Facilitation;       
            $item->text = $request->get('text');
            if(!empty($request->get('text_en'))) {
            $item->text_en = $request->get('text_en');
            }
            $item->save();
            return response(['status' => 1], 200);
        }      }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Facilitation  $facilitation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        if (!empty($request->get('text')) && !empty($request->get('facilitation_id'))) {
            $item = Facilitation::find($request->get('facilitation_id'));       
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
     * @param  \App\Models\Facilitation  $facilitation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Facilitation::find($id);
        $item->delete();
        return response(['status' => 1], 200);
    }
}
