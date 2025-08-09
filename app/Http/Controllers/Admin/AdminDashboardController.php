<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Quote;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get week range (default to current week)
        $weekStart = $request->input('week_start')
            ? Carbon::parse($request->input('week_start'))->startOfWeek()
            : Carbon::now()->startOfWeek();

        $weekEnd = (clone $weekStart)->endOfWeek();

        // Stats
        $totalUsers = User::count();
        $trialUsers = User::where('plan_type', 'trial')->count();
        $subscribedUsers = User::where('plan_type', 'subscribe')
            ->where('is_subscribed', 1)
            ->where('subscription_ends_at', '>', now())
            ->count();

        // Quotes count per day in selected week
        $quotesPerDay = Quote::whereBetween('created_at', [$weekStart, $weekEnd])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        // Fill missing days with 0
        $chartData = [];
        for ($date = $weekStart->copy(); $date->lte($weekEnd); $date->addDay()) {
            $formatted = $date->format('Y-m-d');
            $chartData[] = [
                'day' => $date->format('D'),
                'date' => $formatted,
                'count' => $quotesPerDay[$formatted] ?? 0
            ];
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'trialUsers',
            'subscribedUsers',
            'chartData',
            'weekStart'
        ));
    }

    public function quotes()
    {
        $quotes = Quote::with('user')->latest()->get();
        return view('admin.quotes.index', compact('quotes'));
    }
}
