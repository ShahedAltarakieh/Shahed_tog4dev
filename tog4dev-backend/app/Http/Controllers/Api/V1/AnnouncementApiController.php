<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementApiController extends Controller
{
    public function index(Request $request)
    {
        $target = $request->input('target', $request->input('target_view'));

        $announcements = Announcement::active()
            ->inDate()
            ->forTarget($target)
            ->orderBy('order_no')
            ->orderBy('created_at', 'desc')
            ->get([
                'id', 'title', 'text', 'short_text', 'link', 'cta_text',
                'badge_type', 'target_view', 'source_type',
            ]);

        return response()->json([
            'data' => $announcements,
        ]);
    }
}
