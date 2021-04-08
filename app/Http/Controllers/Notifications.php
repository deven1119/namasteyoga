<?php

    namespace App\Http\Controllers;
    
    use Illuminate\Http\Request;
    
	use App\Models\Notification;
    
	use Auth;
    use DB;
    
    //use Curl;
	
    
    class Notifications extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function listGeneralNotifications()
        {
            //Log::info('General notification list:');
            try{
				$notifications = array();// Notification::orderBy('notification_id','DESC')->paginate(config('app.paging'));
				
				//$notifications = Notification::orderBy('notification_id','DESC')->paginate(Config::get('app.record_per_page'));
				
                return view('notifications.notificationlist', ['notifications' => $notifications]);
            }catch(Exception $e){
                abort(500, $e->message());
            }
        }
		
		
        /**
         * Notification list ajax data tables
         */
        public function notificationIndexAjax(Request $request){
			
            $response = [
              "recordsTotal" => "",
              "recordsFiltered" => "",
              "data" => "",
              "success" => 0,
              "msg" => ""
            ];
            try {
                $start = ($request->start) ? $request->start : 0;
                $end = ($request->length) ? $request->length : 10;
                $search = ($request->search['value']) ? $request->search['value'] : '';
				
                $notifications = Notification::orderBy('id','DESC');
                
				$total = $notifications->count();
				
                if($end==-1){
                  $notifications = $notifications->get();
                }else{
                  $notifications = $notifications->skip($start)->take($end)->get();
                }
                
                $response["recordsFiltered"] = $total;
                $response["recordsTotal"] = $total;
				
                $response["success"] = 1;
                $response["data"] = $notifications;
                
              } catch (Exception $e) {    
      
              }
			  
            return response($response);
        }
		
		public function sendGeneralNotification(Request $request) {
            $status = '';
            $message = '';
            try {
                if ($request->isMethod('post')) {
                    $notification_name = trim($request->input('notification_name'));
                    $notification_message = trim($request->input('notification_message'));
                    
                    if($notification_name !='' || $notification_message !='') {
                        $obj_notif = new Notification;
                        
                        $obj_notif->notification_name = $notification_name;
                        $obj_notif->notification_message = $notification_message;
                        $obj_notif->created_by = Auth::user()->id;

                        $arr_messageInfo['subject'] = $notification_name;
                        $arr_messageInfo['messageBody'] = $notification_message;

                        if( $obj_notif->save() ){
                            $arr_general_device_tokens = DB::table('general_device_tokens')->get();

                            $arr_deviceId = array();
                            foreach($arr_general_device_tokens as $arrId) {
                                $arr_deviceId[] = $arrId->device_id;
                            }

                            $this->sendPushNotification($arr_deviceId, $arr_messageInfo);

                            $status = 1;
                            $message = 'Notification message sent successfully.';
                        }
                    } else {
                        $status = 0;
                        $message = 'Error: Please enter valid informations.';
                    }
                }
                
            } catch (Exception $ex) {
                $status = 0;
                $message = 'Error: Somthing went wrong. Try again.';
            }
            return view('notifications.create', ['status' => $status, 'message' => $message]);
		}
        
        
        
        /*public function phpinfo() {
            echo phpinfo();
            exit;
        }
		*/

    }