<?php

namespace App\Http\Controllers;

use App\Country;
use App\State;
use App\City;
use App\Event;
use App\User;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $obj = new Country();
            $data = $obj->getSelect(true)->paginate(config('app.paging'));
            return view('country.index',['data' => $data]);
        }catch(Exception $e){
            abort(500, $e->message());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            return view('country.create');
        }catch(Exception $e){
          abort(500, $e->message());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $validator = Country::validate($request);
            if ($validator->fails()) {
                return redirect('country/create')
                        ->withErrors($validator)
                        ->withInput();
            }
           Country::create($request->all());
           $request->session()->flash('message.level', 'success');
           $request->session()->flash('message.content', 'Country has been created successfully');
           return redirect('country');
     }catch(Exception $e){
           abort(500, $e->message());
     }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country,$id)
    {
        try{
            $data = $country->where('id','=',$id)->get();
            return view('country.edit',['data' => $data->first()]);
        }catch(Exception $e){
            abort(500, $e->message());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country,$id)
    {
      try{
        /*$validator = Country::validate($request,$id);
        if ($validator->fails()) {
          return redirect('country/edit/'.$id)
                  ->withErrors($validator)
                  ->withInput();
        }*/
        Country::findOrFail($id)->update($request->all());
        $request->session()->flash('message.level', 'success');
        $request->session()->flash('message.content', 'Country has been updated successfully');
        return redirect('country');
      }catch(Exception $e){
            abort(500, $e->message());
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country,$id)
    {
      try{
            Country::findOrFail($id)->delete();
            \Request::session()->flash('message.level', 'success');
            \Request::session()->flash('message.content', 'Country has been deleted successfully');
            return redirect('country');
      }catch(Exception $e){
          abort(500, $e->message());
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Discounttypes  $discounttypes
     * @return \Illuminate\Http\Response
     */
    public function status(Country $country,$id,$status)
    {
      try{
            $msg = 'activated';
            $data = $country->findOrFail($id);
            if(count($data)>0){
              $data->id = $id;
              if($status == '1'){
                  $data->status = '0';
                  $msg = 'deactivated';
              }else{
                  $data->status = '1';
              }
              $data->save();
            }
            \Request::session()->flash('message.level', 'success');
            \Request::session()->flash('message.content', 'Country has been '.$msg.' successfully');
            return redirect('country');
      }catch(Exception $e){
          abort(500, $e->message());
      }
    }

    /**
     * Show the form for get user lsit.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCityList(Request $request){
        try{
            $status = 0;
            $message = "";          
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
                        'lat'=>$v->lat,
                        'lng'=>$v->lng,
                        'state_id'=>$v->State->id,
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
