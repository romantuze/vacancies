<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Work;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id)
    {
        $currencies = [];
        if (!empty($user_id)) {
            $works = Work::where(Work::FIELD_USER_ID,$user_id)->get();
            if (!empty($works[0])) {
                $work = $works[0];
                if (!empty($work->currency)) { 
                    array_push($currencies, $work->currency);
                    return response()->json($currencies);
                }            
            }           
        }
        $all_currencies = Currency::all();
        foreach($all_currencies as $currency) {
            array_push($currencies, $currency->text);
        }
        return response()->json($currencies);
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
            $item = new Currency;       
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
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        if (!empty($request->get('text')) && !empty($request->get('currency_id'))) {
            $item = Currency::find($request->get('currency_id'));       
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
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Currency::find($id);
        $item->delete();
        return response(['status' => 1], 200);
    }
}
