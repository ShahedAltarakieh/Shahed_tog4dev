<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use App\Models\News;
use App\Models\Subscription;
use App\Models\ContactUs;
use App\Models\NewsletterSubscriber;
use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use App\Models\Item;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminSystemController extends Controller
{
    public function activityLogs()
    {
        $recentPayments = Payment::where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get(['id', 'amount', 'payment_type', 'status', 'created_at', 'user_id']);

        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(20)
            ->get(['id', 'first_name', 'last_name', 'email', 'created_at']);

        $recentSubscriptions = Subscription::orderBy('created_at', 'desc')
            ->limit(20)
            ->get(['id', 'status', 'price', 'created_at']);

        $activities = collect();

        foreach ($recentPayments as $p) {
            $activities->push([
                'type' => 'payment',
                'icon' => 'fas fa-credit-card',
                'color' => 'success',
                'title' => __('app.new_payment_received'),
                'description' => number_format($p->amount, 2) . ' JOD via ' . ($p->payment_type ?? 'N/A'),
                'time' => $p->created_at,
                'id' => $p->id,
            ]);
        }

        foreach ($recentUsers as $u) {
            $activities->push([
                'type' => 'user',
                'icon' => 'fas fa-user-plus',
                'color' => 'primary',
                'title' => __('app.new_user_registered'),
                'description' => $u->first_name . ' ' . $u->last_name . ' (' . $u->email . ')',
                'time' => $u->created_at,
                'id' => $u->id,
            ]);
        }

        foreach ($recentSubscriptions as $s) {
            $activities->push([
                'type' => 'subscription',
                'icon' => 'fas fa-sync-alt',
                'color' => $s->status === 'active' ? 'info' : 'warning',
                'title' => __('app.subscription_update'),
                'description' => $s->status . ' - ' . number_format($s->price, 2) . ' JOD',
                'time' => $s->created_at,
                'id' => $s->id,
            ]);
        }

        $activities = $activities->sortByDesc('time')->take(50)->values();

        return view('admin.system.activity-logs', compact('activities'));
    }

    public function notifications()
    {
        $pendingContacts = ContactUs::where('is_read', 0)->count();
        $failedPayments = Payment::where('status', '!=', 'approved')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();
        $newSubscribers = NewsletterSubscriber::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $newUsers = User::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $inactiveSubscriptions = Subscription::where('status', 'inactive')
            ->where('updated_at', '>=', Carbon::now()->subDays(7))
            ->count();

        $notifications = collect();

        if ($pendingContacts > 0) {
            $notifications->push([
                'type' => 'alert',
                'icon' => 'fas fa-envelope',
                'color' => 'warning',
                'title' => $pendingContacts . ' ' . __('app.unread_messages'),
                'description' => __('app.pending_contact_messages'),
                'action_url' => route('contact_us.index', ['type' => 'projects']),
                'action_label' => __('app.view_all'),
                'time' => now(),
            ]);
        }

        if ($failedPayments > 0) {
            $notifications->push([
                'type' => 'alert',
                'icon' => 'fas fa-exclamation-triangle',
                'color' => 'danger',
                'title' => $failedPayments . ' ' . __('app.failed_payments_week'),
                'description' => __('app.review_failed_payments'),
                'action_url' => route('payments.index'),
                'action_label' => __('app.view_all'),
                'time' => now(),
            ]);
        }

        if ($newUsers > 0) {
            $notifications->push([
                'type' => 'info',
                'icon' => 'fas fa-user-plus',
                'color' => 'success',
                'title' => $newUsers . ' ' . __('app.new_users_week'),
                'description' => __('app.new_registrations_this_week'),
                'action_url' => route('users.index'),
                'action_label' => __('app.view_all'),
                'time' => now(),
            ]);
        }

        if ($newSubscribers > 0) {
            $notifications->push([
                'type' => 'info',
                'icon' => 'fas fa-newspaper',
                'color' => 'info',
                'title' => $newSubscribers . ' ' . __('app.new_newsletter_subscribers'),
                'description' => __('app.newsletter_growth_week'),
                'action_url' => route('newsletter.index'),
                'action_label' => __('app.view_all'),
                'time' => now(),
            ]);
        }

        if ($inactiveSubscriptions > 0) {
            $notifications->push([
                'type' => 'warning',
                'icon' => 'fas fa-pause-circle',
                'color' => 'warning',
                'title' => $inactiveSubscriptions . ' ' . __('app.cancelled_subscriptions'),
                'description' => __('app.subscriptions_cancelled_week'),
                'action_url' => '/subscriptions/inactive',
                'action_label' => __('app.view_all'),
                'time' => now(),
            ]);
        }

        return view('admin.system.notifications', compact('notifications', 'pendingContacts', 'failedPayments', 'newUsers', 'newSubscribers', 'inactiveSubscriptions'));
    }

    public function settings()
    {
        return view('admin.system.settings');
    }

    public function systemHealth()
    {
        $stats = [
            'total_users' => User::count(),
            'total_payments' => Payment::where('status', 'approved')->count(),
            'total_revenue' => Payment::where('status', 'approved')->sum('amount'),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'total_news' => News::count(),
            'total_photos' => GalleryPhoto::count(),
            'total_videos' => GalleryVideo::count(),
            'total_items' => Item::count(),
            'total_categories' => Category::count(),
            'today_payments' => Payment::where('status', 'approved')->whereDate('created_at', Carbon::today())->count(),
            'today_revenue' => Payment::where('status', 'approved')->whereDate('created_at', Carbon::today())->sum('amount'),
            'today_users' => User::whereDate('created_at', Carbon::today())->count(),
            'failed_payments_today' => Payment::where('status', '!=', 'approved')->whereDate('created_at', Carbon::today())->count(),
        ];

        $php_version = phpversion();
        $laravel_version = app()->version();
        $db_size = 'N/A';

        try {
            $dbName = config('database.connections.pgsql.database', env('DB_DATABASE'));
            $result = DB::select("SELECT pg_size_pretty(pg_database_size(?)) as size", [$dbName]);
            $db_size = $result[0]->size ?? 'N/A';
        } catch (\Exception $e) {
            $db_size = 'N/A';
        }

        $monthly_revenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenue = Payment::where('status', 'approved')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');
            $monthly_revenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => round($revenue, 2),
            ];
        }

        $monthly_users = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $monthly_users[] = [
                'month' => $date->format('M Y'),
                'count' => $count,
            ];
        }

        return view('admin.system.system-health', compact('stats', 'php_version', 'laravel_version', 'db_size', 'monthly_revenue', 'monthly_users'));
    }

    public function reportsCenter()
    {
        $totalRevenue = Payment::where('status', 'approved')->sum('amount');
        $totalUsers = User::count();
        $totalPayments = Payment::where('status', 'approved')->count();
        $avgPayment = $totalPayments > 0 ? round($totalRevenue / $totalPayments, 2) : 0;

        $topPaymentMethods = Payment::selectRaw('LOWER(payment_type) as method, COUNT(*) as count, SUM(amount) as total')
            ->where('status', 'approved')
            ->groupBy(DB::raw('LOWER(payment_type)'))
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenue = Payment::where('status', 'approved')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');
            $txCount = Payment::where('status', 'approved')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $users = User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $monthlyData[] = [
                'month' => $date->format('M Y'),
                'short' => $date->format('M'),
                'revenue' => round($revenue, 2),
                'transactions' => $txCount,
                'users' => $users,
            ];
        }

        return view('admin.system.reports', compact('totalRevenue', 'totalUsers', 'totalPayments', 'avgPayment', 'topPaymentMethods', 'monthlyData'));
    }

    public function mediaLibrary()
    {
        $photos = GalleryPhoto::orderBy('created_at', 'desc')->limit(50)->get();
        $videos = GalleryVideo::orderBy('created_at', 'desc')->limit(50)->get();
        $totalPhotos = GalleryPhoto::count();
        $totalVideos = GalleryVideo::count();

        return view('admin.system.media-library', compact('photos', 'videos', 'totalPhotos', 'totalVideos'));
    }
}
