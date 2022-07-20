<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\SessionTable;
use carbon\carbon;

class SessionService{

    public function createSession(Request $request){

        date_default_timezone_set($request->timezone?$request->timezone:'Asia/Calcutta');
        // dd('check here');
        $user = SessionTable::create([
            'logged_in' =>($request->userid==0 ? 0 : 1),   
            'userid'	=>($request->userid==0 ? 0 : $request->userid),
            'timezone'  =>$request->timezone,
            'sstart'    =>carbon::now(),
            'smobile'   =>@$request->smobile,
            'sapp'      =>@$request->sapp,
            'svertical' =>@$request->svertical
        ]);
        return $user;
    }

    public function updateSession(Request $request){
        // Create user
        $sessionData = SessionTable::where('sessionid',$request->sessionid)
                                    ->first();

        date_default_timezone_set($request->timezone);

        $endDate   =  carbon::now();

        $startDate =  carbon::parse($sessionData['sstart']);

        $diffInSecond = $endDate->diffInSeconds($startDate);

        $user = SessionTable::where('sessionid',$request->sessionid)
                    ->update([
                        // 'logged_in' =>($request->userid==0 ? 0 : 1),   
                        // 'userid'    =>($request->userid==0 ? 0 : $request->userid),
                        // 'timezone'  =>$request->timezone,
                        'sstart'    =>$startDate->format('m/d/Y h:i:s'),
                        'send'      =>$endDate->format('m/d/Y h:i:s'),
                        'sduration' =>$diffInSecond
                        // 'snumitems' =>$request->'
                        // 'scountry'  =>@$request->scountry,
                        // 'smobile'   =>@$request->smobile,
                        // 'sapp'      =>@$request->sapp,
                        // 'svertical' =>@$request->svertical
                ]);

        return $user;
    }

}
