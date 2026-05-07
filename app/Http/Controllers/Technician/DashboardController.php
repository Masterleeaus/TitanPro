<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class DashboardController extends Controller
{
    public function index(Request $request): Response|ResponseFactory
    {
        $user = $request->user();
        $assignedUserId = $user->id;

        if ($request->boolean('admin_preview') && $user->hasRole(['owner', 'admin', 'super_admin'])) {
            $previewUser = User::query()
                ->where('organization_id', $user->organization_id)
                ->whereHas('roles', fn ($query) => $query->where('name', 'technician'))
                ->when(
                    $request->filled('technician_id'),
                    fn ($query) => $query->whereKey((int) $request->input('technician_id'))
                )
                ->orderBy('name')
                ->first();

            if ($previewUser) {
                $assignedUserId = $previewUser->id;
            }
        }

        $todayCount = Job::where('assigned_to', $assignedUserId)
            ->whereDate('scheduled_at', today())
            ->whereNotIn('status', [Job::STATUS_CANCELLED])
            ->count();

        $inProgressCount = Job::where('assigned_to', $assignedUserId)
            ->where('status', Job::STATUS_IN_PROGRESS)
            ->count();

        return inertia('Technician/Dashboard', [
            'stats' => [
                'today_jobs'  => $todayCount,
                'in_progress' => $inProgressCount,
            ],
        ]);
    }
}
