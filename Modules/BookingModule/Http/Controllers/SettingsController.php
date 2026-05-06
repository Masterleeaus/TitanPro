<?php

namespace Modules\BookingModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\BookingModule\Entities\AppointmentStaffCapacity;
use Modules\BookingModule\Http\Requests\UpdateAutoAssignSettingsRequest;
use Modules\BookingModule\Http\Requests\UpdateNotificationPreferencesRequest;
use Modules\BookingModule\Http\Requests\UpdatePublicSpamSettingsRequest;
use Modules\BookingModule\Services\AppointmentSettingsService;
use Modules\BookingModule\Services\NotificationPreferencesService;
use Modules\BookingModule\Services\ScheduleCapacityService;
use Modules\UserManagement\Entities\User;

class SettingsController extends Controller
{
    public function __construct(protected AppointmentSettingsService $settings)
    {
        $this->middleware(['auth']);
    }

    public function autoAssign()
    {
        $enabled = (bool) $this->settings->get('auto_assign.enabled', config('bookingmodule::auto_assign.enabled', false));
        $strategy = (string) $this->settings->get('auto_assign.strategy', config('bookingmodule::auto_assign.strategy', 'least_busy'));
        $requirePermission = (bool) $this->settings->get('auto_assign.require_permission', config('bookingmodule::auto_assign.require_permission', true));
        $eligiblePermission = (string) $this->settings->get('auto_assign.eligible_permission', config('bookingmodule::auto_assign.eligible_permission', 'appointment.assign'));

        return view('bookingmodule::settings.auto_assign', compact('enabled', 'strategy', 'requirePermission', 'eligiblePermission'));
    }

    public function updateAutoAssign(UpdateAutoAssignSettingsRequest $request)
    {
        $this->settings->set('auto_assign.enabled', (bool) $request->boolean('enabled'));
        $this->settings->set('auto_assign.strategy', $request->input('strategy'));
        $this->settings->set('auto_assign.require_permission', (bool) $request->boolean('require_permission'));
        $this->settings->set('auto_assign.eligible_permission', $request->input('eligible_permission', 'appointment.assign'));

        return redirect()->back()->with('success', __('bookingmodule::settings.saved'));
    }

    public function legacyImport()
    {
        return view('bookingmodule::settings.legacy_import');
    }

    public function publicSpam()
    {
        if (!\Modules\BookingModule\Support\AppointmentPermission::check(auth()->user(), 'appointment settings manage')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        $enable_honeypot = (bool) $this->settings->get('public.enable_honeypot', true);
        $honeypot_min_seconds = (int) $this->settings->get('public.honeypot_min_seconds', (int) config('bookingmodule.public.honeypot_min_seconds', 3));
        $rate_limit_per_minute = (int) $this->settings->get('public.rate_limit_per_minute', (int) config('bookingmodule.public.rate_limit_per_minute', 60));

        return view('bookingmodule::settings.public_spam', compact('enable_honeypot', 'honeypot_min_seconds', 'rate_limit_per_minute'));
    }

    public function updatePublicSpam(UpdatePublicSpamSettingsRequest $request)
    {
        $this->settings->set('public.enable_honeypot', (bool) $request->boolean('enable_honeypot'));
        $this->settings->set('public.honeypot_min_seconds', (int) $request->input('honeypot_min_seconds'));
        $this->settings->set('public.rate_limit_per_minute', (int) $request->input('rate_limit_per_minute'));

        return redirect()->back()->with('success', __('Settings updated.'));
    }

    public function notificationPreferences(NotificationPreferencesService $prefs)
    {
        $companyId = function_exists('company') && company() ? company()->id : null;
        $pref = $prefs->getForUser(auth()->id(), $companyId);

        return view('bookingmodule::settings.notification_preferences', compact('pref'));
    }

    public function updateNotificationPreferences(UpdateNotificationPreferencesRequest $request, NotificationPreferencesService $prefs)
    {
        $companyId = function_exists('company') && company() ? company()->id : null;

        $data = $request->validated();
        foreach ([
            'channel_email', 'channel_database', 'notify_assigned', 'notify_reassigned', 'notify_unassigned',
            'notify_rescheduled', 'notify_cancelled', 'daily_digest',
        ] as $key) {
            $data[$key] = (bool) $request->input($key, false);
        }

        $prefs->saveForUser(auth()->id(), $companyId, $data);

        return redirect()->back()->with('success', __('bookingmodule::settings.updated'));
    }

    public function staffCapacity(ScheduleCapacityService $capacity)
    {
        $companyId = function_exists('company') && company() ? company()->id : null;

        $users = User::where('created_by', creatorId())
            ->where('workspace_id', getActiveWorkSpace())
            ->emp()
            ->orderBy('name')
            ->get(['id', 'name']);

        $capacities = AppointmentStaffCapacity::query()
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->where('created_by', creatorId())
            ->where('workspace', getActiveWorkSpace())
            ->get()
            ->keyBy('user_id');

        return view('bookingmodule::settings.staff_capacity', compact('users', 'capacities'));
    }

    public function updateStaffCapacity(Request $request, ScheduleCapacityService $capacity)
    {
        $request->validate([
            'user_id' => ['required', 'integer'],
            'max_per_day' => ['nullable', 'integer', 'min:0'],
            'max_per_slot' => ['nullable', 'integer', 'min:0'],
            'enforce_conflicts' => ['nullable', 'boolean'],
            'count_pending_too' => ['nullable', 'boolean'],
        ]);

        $companyId = function_exists('company') && company() ? company()->id : null;

        AppointmentStaffCapacity::updateOrCreate(
            [
                'company_id' => $companyId,
                'created_by' => creatorId(),
                'workspace' => getActiveWorkSpace(),
                'user_id' => (int) $request->input('user_id'),
            ],
            [
                'max_per_day' => $request->filled('max_per_day') ? (int) $request->input('max_per_day') : null,
                'max_per_slot' => $request->filled('max_per_slot') ? (int) $request->input('max_per_slot') : null,
                'enforce_conflicts' => (bool) $request->boolean('enforce_conflicts', true),
                'count_pending_too' => (bool) $request->boolean('count_pending_too', false),
            ]
        );

        return redirect()->back()->with('success', __('Settings updated.'));
    }
}
