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
                'id',
                'title', 'title_ar',
                'text', 'text_ar',
                'short_text', 'short_text_ar',
                'link',
                'cta_text', 'cta_text_ar',
                'badge_type', 'target_view', 'source_type', 'created_at',
            ]);

        $data = $announcements->map(function ($item) {
            return [
                'id'             => $item->id,
                'title'          => $item->title,
                'title_ar'       => $item->title_ar,
                'text'           => $item->text,
                'text_ar'        => $item->text_ar,
                'short_text'     => $item->short_text,
                'short_text_ar'  => $item->short_text_ar,
                'link'           => $item->link,
                'cta_text'       => $item->cta_text,
                'cta_text_ar'    => $item->cta_text_ar,
                'badge_type'     => $item->badge_type,
                'target_view'    => $item->target_view,
                'source_type'    => $item->source_type,
                'created_at'     => $item->created_at,
                'reading_time'   => calculateReadingTime($item->getLocalizedText() ?? ''),
            ];
        });

        return response()->json([
            'data' => $data,
        ]);
    }
}
