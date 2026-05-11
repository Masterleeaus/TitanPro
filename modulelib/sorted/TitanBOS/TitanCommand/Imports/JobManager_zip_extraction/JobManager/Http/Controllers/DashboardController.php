<?php

namespace Modules\JobManager\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\JobManager\Entities\Job;


namespace ModulesJobManagerHttpControllers;


namespace Modules\JobManager\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\JobManager\Entities\Job;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();

        return view('jobmanager::dashboard.index', [
            'openJobs' => Job::where('status','open')->count(),
            'todayAppointments' => Job::whereDate('start_date', $now->toDateString())->count(),
            'pendingCompliance' => Job::where('compliance_status','pending')->count(),
            'monthlyRevenue' => Job::whereMonth('completed_at', $now->month)->sum('total_amount'),
            'upcomingJobs' => Job::whereDate('start_date','>=', $now->toDateString())->orderBy('start_date')->take(5)->get(),
            'recentActivity' => Job::orderByDesc('updated_at')->take(5)->get(),
        ]);
    }

    public function upcomingWeek()
    {
        $start = now()->startOfWeek();
        $end = now()->endOfWeek();
        $rows = Job::whereBetween('start_date', [$start, $end])->orderBy('start_date')->get();
        return response()->json(['range'=>[$start->toDateString(), $end->toDateString()], 'data'=>$rows]);
    }
}
