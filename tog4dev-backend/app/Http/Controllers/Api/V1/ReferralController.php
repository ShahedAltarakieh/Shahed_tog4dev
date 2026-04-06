<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Influencer;
use App\Models\ReferralVisit;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
	public function store(Request $request){
        $user = auth('sanctum')->user();;

		$user_agent = $request->userAgent();
        $param = $request->get("param");
        $temp_id = $request->get("temp_id");
        $ip = $request->get("ip");
        $user_id = ($user) ? $user->id : null;

        $referrer = Influencer::where("code", $param)->first();
        if($referrer){
            $visit = ReferralVisit::where('ip', $ip)
                ->where('referrer_id', $referrer->id)->first();
            if(!$visit){
                $need_insert = true;
            } else{
                $need_insert = false;
            }

            if($need_insert){
                $visit = ReferralVisit::where('temp_id', $temp_id)
                    ->where('referrer_id', $referrer->id)->first();
                if(!$visit){
                    $need_insert = true;
                } else{
                    $need_insert = false;
                }
            }

            if($need_insert){
                $visit = ReferralVisit::create([
                    'ip' => $ip,
                    'temp_id' => $temp_id,
                    'referrer_id' => $referrer->id,
                    'user_agent' => $user_agent,
                    'user_id' => $user_id
                ]);
                return response()->json(['data' => $visit]);
            }
        } else {
            return response()->json(['message' => 'not found'], 404);
        }
	}
}
