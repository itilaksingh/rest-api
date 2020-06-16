<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\User; 
use App\ServicesModel;
use App\ProviderServicesModel;
use App\ServiceRequestModel;
use Validator;
class ServiceController extends Controller
{
    
    public function index()
    {
    	$services=ServicesModel::all();
    	return response()->json(['success' => $services], 200);    	
    }


    public function show()
    {
    	$user = Auth::user();
    	$user_model=User::find($user->id);
    	$services=$user_model->services;
    	if(count($services) > 0){
    		foreach ($services as $service) {
    			$service->delete_url=url('api/provider/service/delete/'.$service->id);
    		}
    	}
    	

    	return response()->json(['success'=>$services], 200);    	
    }

    public function delete_service(Request $request)
    {
    	$del=ProviderServicesModel::find($request->id)->delete();
    	if($del){
				return response()->json(['success'=>'Services removed successfully'], 200); 
        }else{
        	return response()->json(['error'=>'Unable to delete service'], 401); 
        }
    }


     public function update(Request $request) 
    { 

        $validator = Validator::make($request->all(),[ 
            'service_id_fk' => 'required',             
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $services=ServicesModel::find($request->service_id_fk); 
        $user = Auth::user();

        if (!empty($request->price)) { $services->price=$request->price; }                
        // if (!empty($request->service)) { $services->title=$request->service; }

        $service_provider=new ProviderServicesModel();
        $service_provider->user_id_fk=$user->id;
        $service_provider->service_id_fk=$services->id;
        $service_provider->service=$services->title;
        $service_provider->price=$services->price;
        
        if($service_provider->save()){
				return response()->json(['success'=>$service_provider], 200); 
        }else{
        	return response()->json(['error'=>'Unable to update service'], 401); 
        }
        
    }


    

    public function customer_service_list()
    {
    	$customer = Auth::user();

    	$provider_list=User::where('role', '=', 1)->select('id','latitude', 'longitude')->get();

    	$near_provider=array();
    	if (count($provider_list) > 0) {
    		foreach ($provider_list as $key => $provider) {
    			
    		$km_unit=$this->distance_in_km($customer->latitude, $customer->longitude, $provider->latitude, $provider->longitude);
    		if($km_unit <= 2){
    				$near_provider[]=$provider->id;
    			}
    		}
    	}

    	$service_list=ProviderServicesModel::whereIn('user_id_fk', $near_provider)->select('id', 'service', 'price')->get();
	    	foreach ($service_list as $service) {	    		
	    		$service->request=url('api/send/request/'.$service->id);
	    	}

    	return response()->json(['success'=>$service_list], 200); 

    }





    private function distance_in_km($lat1, $lon1, $lat2, $lon2) { 
		$theta = $lon1 - $lon2;
	    $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
	    $miles = acos($miles);
	    $miles = rad2deg($miles);
	    $miles = $miles * 60 * 1.1515;
	    $feet = $miles * 5280;
	    $yards = $feet / 3;
	    $kilometers = $miles * 1.609344;
	    $meters = $kilometers * 1000;
	    return $kilometers;
	}


	public function send_request(Request $request)
	{

		$customer = Auth::user();
		$service_provider_id=$request->service_id;

		$is_exsting=ServiceRequestModel::where('user_id_fk', '=', $customer->id)
								->where('service_provider_id', '=', $service_provider_id)
								->count();

		if($is_exsting > 0){
			return response()->json(['error'=>'Request already has been sent.'], 401);
		}

		$send_req=new ServiceRequestModel();

		$send_req->user_id_fk=$customer->id;
		$send_req->service_provider_id=$service_provider_id;

		if($send_req->save()){

			return response()->json(['success'=>'Request has been sent. Thank you'], 200);

		}else{
			return response()->json(['error'=>'Try after sometime.'], 401);

		}
					

	}


	public function accept_request(Request $request)
	{
		$obj=ServiceRequestModel::find($request->request_id);
		//reject status is 2
		$obj->status=2;
		if($obj->save()){
			return response()->json(['success'=>'Request has been accepted. Thank you'], 200);
		}else{
			return response()->json(['error'=>'Try after sometime.'], 401);
		}
		
	}

	public function reject_request(Request $request)
	{
		$obj=ServiceRequestModel::find($request->request_id);
		//reject status is 3
		$obj->status=3;
		if($obj->save()){
			return response()->json(['success'=>'Request has been accepted. Thank you'], 200);
		}else{
			return response()->json(['error'=>'Try after sometime.'], 401);
		}
		
	}


}
