<?php

namespace App\Extensions\TitanCommand\System\JobManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Extensions\TitanCommand\System\JobManager\Entities\Job;






class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $mtdRevenue = Job::whereNotNull('completed_at')
            ->whereYear('completed_at', now()->year)
            ->whereMonth('completed_at', now()->month)
            ->sum('total_amount');

        $openJobs = Job::where('status', 'open')->count();
        $pendingCompliance = Job::where('compliance_status', 'pending')->count();

        return view('jobmanager::reports.index', compact('mtdRevenue','openJobs','pendingCompliance'));
    }

    public function revenue(Request $request)
    {
        $year = (int)($request->get('year', now()->year));
        $rows = Job::selectRaw('MONTH(completed_at) as m, SUM(total_amount) as total')
            ->whereNotNull('completed_at')
            ->whereYear('completed_at', $year)
            ->groupBy('m')
            ->orderBy('m')
            ->get();

        return view('jobmanager::reports.revenue', compact('rows','year'));
    }

    public function compliance(Request $request)
    {
        $rows = Job::select('id','title','compliance_status','updated_at')
            ->whereIn('compliance_status', ['pending','failed'])
            ->orderByDesc('updated_at')
            ->limit(100)
            ->get();

        return view('jobmanager::reports.compliance', compact('rows'));
    }

    public function timeline()
    {
        $jobs = Job::orderBy('start_date','asc')->get()->groupBy(function($j){
            return \Carbon\Carbon::parse($j->start_date)->format('W-Y');
        });
        return view('jobmanager::reports.timeline', compact('jobs'));
    }

    public function audit()
    {
        $logFile = storage_path('logs/laravel.log');
        $lines = [];
        if (file_exists($logFile)) {
            $lines = array_slice(file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES), -50);
        }
        return view('jobmanager::reports.audit', ['lines'=>$lines]);
    }
}
