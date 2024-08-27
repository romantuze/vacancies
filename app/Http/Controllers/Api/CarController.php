<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Car::all()->toArray());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $classifier_edit = config('app.classifier_edit'); 
        if(!classifier_edit) {
            return response(['status' => 0], 200); 
        }
            
        if(!empty($request->get('text'))) {
            $item = new Car;       
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
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        $classifier_edit = config('app.classifier_edit'); 
        if(!classifier_edit) {
            return response(['status' => 0], 200); 
        }

        if (!empty($request->get('text')) && !empty($request->get('car_id'))) {
            $item = Car::find($request->get('car_id'));       
            if(!empty($item)) {
                $item->text = $request->get('text');
                if(!empty($request->get('text_en'))) {
                    $item->text_en = $request->get('text_en');
                } else {
                    $item->text_en = "";
                }
                $item->save();     
                return response(['status' => 1, 'msg' => __('text.ClassifierChanged')], 200);           
            }           
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
        $classifier_edit = config('app.classifier_edit'); 
        if(!classifier_edit) {
            return response(['status' => 0], 200); 
        }

        $item = Car::find($id);
        $item->delete();
        return response(['status' => 1], 200);
    }
}
