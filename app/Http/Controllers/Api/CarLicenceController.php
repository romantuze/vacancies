<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\CarLicence;
use Illuminate\Http\Request;

class CarLicenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(CarLicence::all()->toArray());
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
            $item = new CarLicence;       
            $item->text = $request->get('text');
            if(!empty($request->get('text_en'))) {
                $item->text_en = $request->get('text_en');
            } else {
                $item->text_en = "";
            }
            $item->save();
            return response(['status' => 1], 200);
        }  
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CarLicence  $carLicence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        if (!empty($request->get('text')) && !empty($request->get('car_licence_id'))) {
            $item = CarLicence::find($request->get('car_licence_id'));       
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
     * @param  \App\Models\CarLicence  $carLicence
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = CarLicence::find($id);
        $item->delete();
        return response(['status' => 1], 200);
    }
}
