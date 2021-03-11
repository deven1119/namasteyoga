<?php

    namespace App\Http\Controllers\v5;
    
    use App\Event;
    use App\State;
    use App\City;
    use App\Country;
    use App\User;
    use App\NotifyMe;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Http\Request;
    use JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use App\Http\Controllers\Traits\SendMail;
    use Config;
    use App\Common\Utility;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\DB;
    use App\duplicateRequest;

    class EventController extends Controller
    {
         
    }