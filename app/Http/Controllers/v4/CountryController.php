<?php

namespace App\Http\Controllers\v4;

use App\Country;
use App\State;
use App\City;
use App\Event;
use App\User;
use Illuminate\Http\Request;

class CountryController extends Controller
{

    /**
     * Show the form for get user lsit.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCityList(Request $request){
        try{
            $status = 0;
            $message = "";

            if(!$this->verifyChecksum($request)){
                return response()->json(["status"=>0,
                "message"=>'checksum not verified',
                "data"=>json_decode('{}')]);
            }

            $search = "";
            $search = $request->search;
            if(isset($request->type) && $request->type=="event"){

                $cityObj = new City();
                $returnData  = $cityObj->getCountryStateCityByName($request);
                if($returnData['error']==0 && $returnData['success']==1){
                    $country_id = $returnData['data']['country_id'];
                    $state_id = $returnData['data']['state_id'];
                    $city_id = $returnData['data']['city_id'];
                }else{
                    return response()->json(['status'=>$status,'message'=>$returnData['message'],'data'=>[]]);
                }

                $events = Event::where([
                    ['event_name', 'LIKE', $search . '%'],
                    ['country_id', $country_id ],
                    ['state_id', $state_id],
                    ['city_id', $city_id],
                    ['mode', 'FREE']
                    ])->take(10)->get();
                $listArr = [];
                if($events->count() > 0){
                    foreach($events as $k=>$v){
                        //event_name contact_person  contact_no email

                        $events[$k]->city_id = $v->City->id;
                        $events[$k]->city_name = $v->City->name;
                        $events[$k]->state_id = $v->State->id;
                        $events[$k]->state_name = $v->State->name;
                        $events[$k]->country_id = $v->Country->id;
                        $events[$k]->country_name = $v->Country->name;

                        $ratingData = $this->getRating('event',$v->id);
                        if(count($ratingData)>0){
                            $events[$k]->rating = $ratingData['rating'];
                            $events[$k]->out_of = $ratingData['out_of'];
                        }
                        //$events[$k]->rating = $this->getRating('event',$v->id);


                        unset($events[$k]->State);
                        unset($events[$k]->Country);
                        unset($events[$k]->City);
                    }
                }
                $status = 1;
                return response()->json(['status'=>$status,'message'=>$message,'data'=>$events]);
            }else if(isset($request->type) && ($request->type=="trainer" || $request->type=="center")){

                $cityObj = new City();
                $returnData  = $cityObj->getCountryStateCityByName($request);
                if($returnData['error']==0 && $returnData['success']==1){
                    $country_id = $returnData['data']['country_id'];
                    $state_id = $returnData['data']['state_id'];
                    $city_id = $returnData['data']['city_id'];
                }else{
                    return response()->json(['status'=>$status,'message'=>$returnData['message'],'data'=>[]]);
                }

                if($request->type=="center"){
                    $cond = [
                        ["status","1"],
                        ["role_id",2],
                        ['name','LIKE',$search.'%'],
                        ['country_id', $country_id],
                        ['state_id', $state_id],
                        ['city_id', $city_id]]
                        ;  //2 for trainer
                }else{
                    $cond = [
                        ["status","1"],
                        ["role_id",3],
                        ["ycb_approved",'1'],
                        ["suspended",'0'],
                        ['name','LIKE',$search.'%'],
                        ['country_id', $country_id ],
                        ['state_id', $state_id],
                        ['city_id', $city_id]];   //3 for center
                }
                //$cond = [["status","1"],["role_id",2]];  //2 for trainer
                $events = User::with('Country.State.City')->where($cond)->take(10)->get();
                $listArr = [];
                if($events->count() > 0){
                    foreach($events as $k=>$v){
                        $events[$k]->city_id = $v->City->id;
                        $events[$k]->city_name = $v->City->name;
                        $events[$k]->state_id = $v->State->id;
                        $events[$k]->state_name = $v->State->name;
                        $events[$k]->country_id = $v->Country->id;
                        $events[$k]->country_name = $v->Country->name;
                        unset($events[$k]->Country);
                        unset($events[$k]->State);
                        unset($events[$k]->City);
                    }
                }
                $status = 1;
                return response()->json(['status'=>$status,'message'=>$message,'data'=>$events]);
            }else{

                $cities = City::with('State.Country')->where('name', 'LIKE', $search . '%')->take(10)->get();
                $listArr = [];
                if(count($cities)>0){
                    foreach($cities as $k=>$v){
                        $listArr[$k] = ['city_id'=>$v->id,
                        'city_name'=>$v->name,
                        'state_id'=>$v->State->id,
                        'lat'=>$v->lat,
                        'lng'=>$v->lng,
                        'state_name'=>$v->State->name,
                        'country_id'=>$v->state->country->id,
                        'country_name' => $v->state->country->name
                        ];
                    }
                    //echo '<pre>';print_r($listArr); die;
                }
                //$users[0]->role
                $status = 1;
                return response()->json(['status'=>$status,'message'=>$message,'data'=>$listArr]);
            }

        }catch(Exception $e){
            return response()->json(['status'=>$status,'message'=>'city list exception','data'=>json_decode('{}')]);
        }

    }
}
