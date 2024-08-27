<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\City;
use App\Models\Region;

class CountryController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCountries()
    {
        return response()->json(Country::all()->toArray());
    }

    public function getCitiesAll()
    {
        return response()->json(City::all()->toArray());
    }


    public function getRegionsAll()
    {
        return response()->json(Region::all()->toArray());
    }

    /**
     * @param $countryId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRegions($countryId)
    {
        return response()->json(Region::where('country_id', $countryId)->get()->toArray());
    }

    /**
     * @param $regionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCities($regionId)
    {
        return response()->json(City::where('region_id', $regionId)->get()->toArray());
    }


    public function updateCountry(Request $request) {
        if (!empty($request->get('text')) && !empty($request->get('country_id'))) {
            $item = Country::find($request->get('country_id'));       
            if(!empty($item)) {
                $item->text = $request->get('text');
                if(!empty($request->get('text_en'))) {
                    $item->text_en = $request->get('text_en');
                }
                $item->save();
            }
            return response(['status' => 1], 200);
        }    
    }


    public function storeCountry(Request $request) {
        if(!empty($request->get('text'))) {
            $item = new Country;       
            $item->text = $request->get('text');
            if(!empty($request->get('text_en'))) {
                $item->text_en = $request->get('text_en');
            }
            $item->save();
            return response(['status' => 1], 200);
        }          
    }

    public function destroyCountry($countryId) {
        $regions = Region::where('country_id', $countryId)->get()->toArray();
        if(!empty($regions)) {
            return response(['status' => 0, 'msg' => 'В регионе остались неудаленные регионы'], 200); 
        }

        if(!empty($countryId)) {
          $country = Country::find($countryId);        
           if(!empty($country)) {
                $country = Country::destroy($countryId);
                return response(['status' => 1, 'msg' => 'Страна удалена'], 200);   
            }         
        }          
    }

    public function destroyRegion($regionId) {
        $cities = City::where('region_id', $regionId)->get()->toArray();
        if(!empty($cities)) {
            return response(['status' => 0, 'msg' => 'В регионе остались неудаленные города'], 200); 
        }
        if(!empty($regionId)) {
          $region = Region::find($regionId);        
           if(!empty($region)) {
                $region = Region::destroy($regionId);
                return response(['status' => 1, 'msg' => 'Регион удален'], 200);   
            }         
        }          
    }

    public function destroyCity($cityId) {
        if(!empty($cityId)) {
            $city = City::find($cityId);        
            if(!empty($city)) {
                $city = City::destroy($cityId);
                return response(['status' => 1, 'msg' => 'Город удален'], 200);   
            }         
        }          
    }

    public function storeRegion(Request $request) {
        if(!empty($request->get('text')) && !empty($request->get('country_id'))) {
            $item = new Region;       
            $item->text = $request->get('text');
            $item->country_id = (int) $request->get('country_id');
            if(!empty($request->get('text_en'))) {
                $item->text_en = $request->get('text_en');
            }
            $item->save();
            return response(['status' => 1], 200);
        }          
    }

    public function updateRegion(Request $request) {
        if (!empty($request->get('text')) && !empty($request->get('region_id'))) {
            $item = Region::find($request->get('region_id'));       
            if(!empty($item)) {
                $item->text = $request->get('text');
                if(!empty($request->get('text_en'))) {
                    $item->text_en = $request->get('text_en');
                }
                $item->save();
                return response(['status' => 1], 200);
            }           
        }    
    }

    public function storeCity(Request $request) {
        if(!empty($request->get('text')) && !empty($request->get('region_id'))) {
            $item = new City;       
            $item->text = $request->get('text');
            $item->region_id = (int) $request->get('region_id');
            if(!empty($request->get('text_en'))) {
            $item->text_en = $request->get('text_en');
            }
            $item->save();
            return response(['status' => 1], 200);
        }          
    }

    public function updateCity(Request $request) {
        if (!empty($request->get('text')) && !empty($request->get('city_id'))) {
            $item = City::find($request->get('city_id'));       
            if(!empty($item)) {
                $item->text = $request->get('text');
                if(!empty($request->get('text_en'))) {
                    $item->text_en = $request->get('text_en');
                }
                $item->save();     
                return response(['status' => 1], 200);           
            }           
        }    
    }

}
