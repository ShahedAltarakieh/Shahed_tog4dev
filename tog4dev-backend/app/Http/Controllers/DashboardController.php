<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Influencer;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use App\Models\News;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exports\SubscriptionsExport;
use App\Exports\PaymentMethodExport;
use App\Exports\PaymentMethodSheetsExport;
use App\Exports\InfluencersExport;
use App\Exports\CountryExport;
use App\Exports\ProjectsExport;
use App\Exports\PaymentsExport;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        $lang = app()->getLocale();

        $list_of_dates = [
            'today' => Carbon::today()->toDateString(),
            'yesterday' => Carbon::yesterday()->toDateString(),
            'this_week_start' => Carbon::now()->startOfWeek(Carbon::SATURDAY)->toDateString(),
            'this_week_end' => Carbon::now()->endOfWeek(Carbon::FRIDAY)->toDateString(),
            'last_week_start' => Carbon::now()->subWeek()->startOfWeek(Carbon::SATURDAY)->toDateString(),
            'last_week_end' => Carbon::now()->subWeek()->endOfWeek(Carbon::FRIDAY)->toDateString(),
            'this_month_start' => Carbon::now()->startOfMonth()->toDateString(),
            'this_month_end' => Carbon::now()->endOfMonth()->toDateString(),
            'last_month_start' => Carbon::now()->subMonth()->startOfMonth()->toDateString(),
            'last_month_end' => Carbon::now()->subMonth()->endOfMonth()->toDateString(),
            'this_year_start' => Carbon::now()->startOfYear()->toDateString(),
            'this_year_end' => Carbon::now()->endOfYear()->toDateString(),
            'last_year_start' => Carbon::now()->subYear()->startOfYear()->toDateString(),
            'last_year_end' => Carbon::now()->subYear()->endOfYear()->toDateString(),
        ];

        $type = $_GET["type"] ?? 6;

        $startDate = request('start_date') ?? $list_of_dates["this_year_start"];
        $endDate = request('end_date') ?? $list_of_dates["this_year_end"];

        if (
            (
                !in_array($startDate, $list_of_dates, true) ||
                !in_array($endDate, $list_of_dates, true) 
            )
            && $type != 9

        ) {
            $type = -1;
        }

        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate   = Carbon::parse($endDate)->endOfDay();

        $payments_today = $this->getToday();
        $payments_week = $this->getWeek();
        $payments_month = $this->getMonth();
        $payments_year = $this->getYear();
        $all_payments = $this->getAllPayments();
        $payments_custom_range = $this->getCustomRange($startDate, $endDate);

        $activeSubscriptions = Subscription::where('status', 'active')->whereNotNull('end_date')->whereBetween('created_at', [$startDate, $endDate])->count();
        $inactiveSubscriptions = Subscription::where('status', 'inactive')->whereNotNull('end_date')->whereBetween('created_at', [$startDate, $endDate])->count();

        // Sum the price for active and inactive subscriptions
        $activeSubscriptionsTotal = Subscription::where('status', 'active')->whereNotNull('end_date')->whereBetween('created_at', [$startDate, $endDate])->sum('price');
        $inactiveSubscriptionsTotal = Subscription::where('status', 'inactive')->whereNotNull('end_date')->whereBetween('created_at', [$startDate, $endDate])->sum('price');

        // Collect all data in JSON format
        $subscriptionData = [
            'activeSubscriptions' => (int) $activeSubscriptionsTotal,
            'inactiveSubscriptions' => (int) $inactiveSubscriptionsTotal,
            'activeSubscriptionsTotal' => $activeSubscriptions,
            'inactiveSubscriptionsTotal' => $inactiveSubscriptions,
            'subscriptionLabels' => [
                __('app.active')." ({$activeSubscriptions} ".__('app.subscriptions').")",
                __('app.inactive')." ({$inactiveSubscriptions} ".__('app.subscriptions').")"
            ]
        ];

        $influencers = Influencer::with(['payments' => function ($query) use ($startDate, $endDate) {
            $query->where('status', 'approved')->whereBetween('created_at', [$startDate, $endDate])
                ->with(['cartItems', 'subscriptions']);
        }])->get();

        $influencers->transform(function ($influencer) {
            $payments = $influencer->payments;

            $active_sum_sub = 0;
            $active_sum_total = 0;
            $inactive_sum_sub = 0;
            $inactive_sum_total = 0;
            $total_one_time_payments = 0;
            $total_monthly_payments = 0;
            foreach($payments as $i => $p){
                $active_sum_sub += $p->subscriptions->where("status", "active")->count();
                $active_sum_total += $p->subscriptions->where("status", "active")->sum("price");
                $inactive_sum_sub += $p->subscriptions->where("status", "inactive")->count();
                $inactive_sum_total += $p->subscriptions->where("status", "inactive")->sum("price");
                $total_one_time_payments += $p->cartItems->where('type', 'one_time')->sum('price');
                $total_monthly_payments += $p->cartItems->where('type', 'monthly')->sum('price');
            }

            return [
                'id' => $influencer->id,
                'name' => $influencer->name,
                'page_link' => $influencer->page_link,
                'active_subscriptions' => $active_sum_sub,
                'active_subscription_total' => $active_sum_total,
                'inactive_subscriptions' => $inactive_sum_sub,
                'inactive_subscription_total' => $inactive_sum_total,
                'number_of_transactions' => $payments->count(),
                'one_time_total' => $total_one_time_payments,
                'subscription_total' => $total_monthly_payments,
                'total_amount' => $total_one_time_payments + $total_monthly_payments
            ];
        });

        $influencers = $influencers->sortByDesc('total_amount')->values();

        $paymentMethodsStats = Payment::selectRaw('LOWER(payment_type) as payment_type')
            ->selectRaw('SUM(amount) as total_amount')
            ->selectRaw('COUNT(user_id) as user_count')
            ->where('status', 'approved')
            ->whereBetween('payments.created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('LOWER(payment_type)'))
            ->orderByDesc('total_amount')
            ->get();

        $paymentMethodCategories = $paymentMethodsStats->pluck('payment_type')->map(function ($type) {
            return ucfirst(strtolower($type)); // Normalize display
        })->toArray();

        $paymentMethodData = $paymentMethodsStats->pluck('total_amount')->map(function ($amount) {
            return (float) $amount;
        })->toArray();

        $paymentMethodUsers = $paymentMethodsStats->pluck('user_count')->toArray();

        $countryStats = DB::table('payment_user_details')
            ->join('payments', 'payments.id', '=', 'payment_user_details.payment_id')
            ->where('payments.status', 'approved')
            ->whereBetween('payments.created_at', [$startDate, $endDate]) // ← Add date filter
            ->select('payment_user_details.country', DB::raw('COUNT(DISTINCT payments.user_id) as user_count'), DB::raw('SUM(payments.amount) as total_amount'))
            ->groupBy('payment_user_details.country')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();

        $countryCategories = $countryStats->pluck('country')->map(function ($type) {
            return ucfirst(strtolower($type));
        })->toArray();

        $countryData = $countryStats->pluck('total_amount')->map(function ($amount) {
            return (float) $amount;
        })->toArray();

        $countryUsers = $countryStats->pluck('user_count')->toArray();

        $categoriesProject = Category::getProjects()->where('all_option', 0)->with(['items.cartItemsPaid'])->get();

        $categoryStats = $categoriesProject->map(function ($category) use ($startDate, $endDate) {
            $carts = $category->items->flatMap(function ($item) use ($startDate, $endDate) {
                return $item->cartItemsPaid
                    ->whereBetween('created_at', [$startDate, $endDate]);
            });
        
            return [
                'category' => $category->getLocalizationTitle(),
                'transactions' => $carts->count(),
                'total_amount' => $carts->sum('price'),
            ];
        });

        $categoriesChart = $categoryStats->pluck('category')->toArray();
        $transactionsCategories = $categoryStats->pluck('transactions')->toArray();
        $totalAmountsCategories = $categoryStats->pluck('total_amount')->map(fn($amount) => round($amount, 2))->toArray();

        $categoriesCrowdfunding = Category::getCrowdfunding()->where('all_option', 0)->with(['items.cartItemsPaid'])->get();

        $categoryCrouwdStats = $categoriesCrowdfunding->map(function ($category) use ($startDate, $endDate) {
            $carts = $category->items->flatMap(function ($item) use ($startDate, $endDate) {
                return $item->cartItemsPaid
                    ->whereBetween('created_at', [$startDate, $endDate]);
            });
        
            return [
                'category' => $category->getLocalizationTitle(),
                'transactions' => $carts->count(),
                'total_amount' => $carts->sum('price'),
            ];
        });

        $categoriesChartCrowd = $categoryCrouwdStats->pluck('category')->toArray();
        $transactionsCategoriesCrowd = $categoryCrouwdStats->pluck('transactions')->toArray();
        $totalAmountsCategoriesCrowd = $categoryCrouwdStats->pluck('total_amount')->map(fn($amount) => round($amount, 2))->toArray();

        
        $categoriesCrowdfunding = Category::getCrowdfunding()->where('all_option', 0)->with('items.cartItemsPaid')->get();

        $categoryCrowdfundingTargets = $categoriesCrowdfunding->map(function ($category) {
            return [
                'id' => $category->id,
                'title' => $category->getLocalizationTitle(),
                'items' => $category->items->map(function ($item) {
                    $totalPaid = $item->cartItemsPaid->sum('price');
                    $totalTransactions = $item->cartItemsPaid->count();
                    $leftTargetRaw = ($totalPaid <= $item->amount) ? ($item->amount - $totalPaid) : 0;

                    return [
                        'item_id' => $item->id,
                        'title' => $item->getLocalizationTitle(),
                        'amount' => $item->amount,
                        'created_at' => Carbon::parse($item->created_at)->format('Y-m-d'),
                        'paid' => (string) $totalPaid,
                        'total_transactions' => $totalTransactions,
                        'is_closed' => ($totalPaid >= $item->amount) ? "Yes" : "No",
                        'left_target' => floor($leftTargetRaw) != $leftTargetRaw ? number_format($leftTargetRaw, 3, ".", "") : $leftTargetRaw,
                    ];
                }),
            ];
        });

        $firstStartDate = Payment::orderBy('created_at', 'asc')->value('created_at');
        $lastEndDate  = Payment::orderBy('created_at', 'desc')->value('created_at');
        if($firstStartDate){
                $firstStartDate = $firstStartDate->format('Y-m-d');
        } else {
                $firstStartDate = '';
        }
        if($lastEndDate){
                $lastEndDate = $lastEndDate->format('Y-m-d');
        } else {
                $lastEndDate = '';
        }

        $totalUsers = User::count();
        $newUsersThisWeek = User::where('created_at', '>=', Carbon::now()->startOfWeek(Carbon::SATURDAY))->count();
        $totalNews = News::count();
        $activeSubscriptionsCount = Subscription::where('status', 'active')->count();
        $pendingContacts = ContactUs::where('is_read', 0)->count();
        $failedPaymentsWeek = Payment::where('status', '!=', 'approved')
            ->where('created_at', '>=', Carbon::now()->subDays(7))->count();

        $recentPayments = Payment::where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['id', 'amount', 'payment_type', 'created_at', 'user_id']);

        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['id', 'name', 'email', 'created_at']);

        $startDate = $startDate->format('Y-m-d');
        $endDate   = $endDate->format('Y-m-d');

        return view('admin.dashboard', compact(
            'type',
            'startDate',
            'endDate',

            'influencers',

            'categoriesChartCrowd',
            'transactionsCategoriesCrowd',
            'totalAmountsCategoriesCrowd',

            'paymentMethodCategories',
            'paymentMethodData',
            'paymentMethodUsers',

            'countryCategories',
            'countryData',
            'countryUsers',

            'list_of_dates',

            'categoriesChart',
            'transactionsCategories',
            'totalAmountsCategories', 

            'categoryCrowdfundingTargets',

            'subscriptionData',
            'payments_today',
            'payments_week',
            'payments_month',
            'payments_year',
            'payments_custom_range',
            'all_payments',

            'firstStartDate',
            'lastEndDate',

            'totalUsers',
            'newUsersThisWeek',
            'totalNews',
            'activeSubscriptionsCount',
            'pendingContacts',
            'failedPaymentsWeek',
            'recentPayments',
            'recentUsers'
        ));

    }

    public function getToday()
    {
        // Define today and yesterday's start & end times
        $todayStart = Carbon::today()->startOfDay(); // 00:00:00
        $todayEnd = Carbon::today()->endOfDay(); // 23:59:59
    
        $yesterdayStart = Carbon::yesterday()->startOfDay();
        $yesterdayEnd = Carbon::yesterday()->endOfDay();
    
        // Get payments for today
        $payments_today = Payment::where('status', 'approved')
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->sum('amount');
    
        // Get payments for yesterday
        $payments_yesterday = Payment::where('status', 'approved')
            ->whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
            ->sum('amount');
    
        // Calculate percentage change
        if ($payments_yesterday > 0) {
            $percentage_change = (($payments_today - $payments_yesterday) / $payments_yesterday) * 100;
        } else {
            $percentage_change = $payments_today > 0 ? 100 : 0; // Avoid division by zero
        }
    
        return [
            'today' => $payments_today,
            'yesterday' => $payments_yesterday,
            'percentage_change' => round($percentage_change, 2)
        ];
    }

    public function getWeek(){
        // Define start and end of this week (Saturday - Friday)
        $thisWeekStart = Carbon::now()->startOfWeek(Carbon::SATURDAY);
        $thisWeekEnd = $thisWeekStart->copy()->addDays(6)->endOfDay();

        // Define start and end of last week (Saturday - Friday)
        $lastWeekStart = $thisWeekStart->copy()->subWeek();
        $lastWeekEnd = $lastWeekStart->copy()->addDays(6)->endOfDay();

        // Get this week's approved payments
        $payments_this_week = Payment::where('status', 'approved')
            ->whereBetween('created_at', [$thisWeekStart, $thisWeekEnd])
            ->sum('amount');

        // Get last week's approved payments
        $payments_last_week = Payment::where('status', 'approved')
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->sum('amount');

        // Calculate percentage change
        if ($payments_last_week > 0) {
            $percentage_change = (($payments_this_week - $payments_last_week) / $payments_last_week) * 100;
        } else {
            $percentage_change = $payments_this_week > 0 ? 100 : 0; // Avoid division by zero
        }

        // Return results
        return [
            'this_week' => $payments_this_week,
            'last_week' => $payments_last_week,
            'percentage_change' => round($percentage_change, 2),
        ];

    }

    public function getMonth()
    {
        // Define start & end of this month
        $thisMonthStart = Carbon::now()->startOfMonth(); // First day of this month, 00:00:00
        $thisMonthEnd = Carbon::now()->endOfMonth(); // Last day of this month, 23:59:59

        // Define start & end of last month
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Get payments for this month
        $payments_this_month = Payment::where('status', 'approved')
            ->whereBetween('created_at', [$thisMonthStart, $thisMonthEnd])
            ->sum('amount');

        // Get payments for last month
        $payments_last_month = Payment::where('status', 'approved')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('amount');

        // Calculate percentage change
        if ($payments_last_month > 0) {
            $percentage_change = (($payments_this_month - $payments_last_month) / $payments_last_month) * 100;
        } else {
            $percentage_change = $payments_this_month > 0 ? 100 : 0; // Avoid division by zero
        }

        return [
            'this_month' => $payments_this_month,
            'last_month' => $payments_last_month,
            'percentage_change' => round($percentage_change, 2)
        ];
    }

    public function getYear()
    {
        // Define start & end of this year
        $thisYearStart = Carbon::now()->startOfYear(); // January 1st, 00:00:00
        $thisYearEnd = Carbon::now()->endOfYear(); // December 31st, 23:59:59

        // Define start & end of last year
        $lastYearStart = Carbon::now()->subYear()->startOfYear();
        $lastYearEnd = Carbon::now()->subYear()->endOfYear();

        // Get payments for this year
        $payments_this_year = Payment::where('status', 'approved')
            ->whereBetween('created_at', [$thisYearStart, $thisYearEnd])
            ->sum('amount');

        // Get payments for last year
        $payments_last_year = Payment::where('status', 'approved')
            ->whereBetween('created_at', [$lastYearStart, $lastYearEnd])
            ->sum('amount');

        // Calculate percentage change
        if ($payments_last_year > 0) {
            $percentage_change = (($payments_this_year - $payments_last_year) / $payments_last_year) * 100;
        } else {
            $percentage_change = $payments_this_year > 0 ? 100 : 0; // Avoid division by zero
        }

        return [
            'this_year' => $payments_this_year,
            'last_year' => $payments_last_year,
            'percentage_change' => round($percentage_change, 2)
        ];
    }

    public function getCustomRange($start, $end)
    {    
        // Get payments for this year
        $payments = Payment::where('status', 'approved')
            ->whereBetween('created_at', [$start, $end])
            ->sum('amount');

        return $payments;
    }

    public function getAllPayments()
    {
        $payments = Payment::where('status', 'approved')
            ->sum('amount');

        return $payments;
    }


    public function downloadPayments(Request $request){
        $type = $request->service_type ?? 'na';
        $start_date = $request->start ?? Carbon::now()->startOfYear()->toDateString();
        $end_date = $request->end ?? Carbon::now()->endOfYear()->toDateString();
        $start_date = Carbon::parse($start_date)->startOfDay();
        $end_date   = Carbon::parse($end_date)->endOfDay();
        switch($type){
            case 'subscriptions':
                return Excel::download(new SubscriptionsExport($start_date, $end_date), 'subscriptions.xlsx');
            break;
            case 'payment-method': 
                return Excel::download(new PaymentMethodExport($start_date, $end_date), 'payment-method.xlsx');
            break;
            case 'countries':
                return Excel::download(new CountryExport($start_date, $end_date), 'countries.xlsx');
            break;
            case 'projects':
                return Excel::download(new ProjectsExport($start_date, $end_date, "projects"), 'projects.xlsx');
            break;
            case 'crowdfunding':
                return Excel::download(new ProjectsExport($start_date, $end_date, "crowdfunding"), 'crowdfunding.xlsx');
            break;
            case 'payments':
                return Excel::download(new PaymentsExport($start_date, $end_date), 'payments.xlsx');
            break;
            case 'influencers':
                return Excel::download(new InfluencersExport($start_date, $end_date), 'influencers.xlsx');
            break;
        }
    }
}
