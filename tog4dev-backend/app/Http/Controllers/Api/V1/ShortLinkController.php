<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ShortLink;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShortLinkController extends Controller
{
    public function index(Request $request, $code)
    {
        try {
            $link = ShortLink::where("short_code", $code)->first();
            if($link){
                return $link;
            } else {
                return response()->json([
                    'message' => 'Not found',
                    'error' => "not found",
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving link.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
