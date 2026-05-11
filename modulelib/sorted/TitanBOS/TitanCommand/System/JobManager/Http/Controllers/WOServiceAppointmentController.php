<?php

namespace App\Extensions\TitanCommand\System\JobManager\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Extensions\TitanCommand\System\JobManager\Entities\WOServiceAppointment;






class WOServiceAppointmentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'work_order_id' => ['required','integer'],
            'technician_id' => ['nullable','integer'],
            'starts_at' => ['nullable','date'],
            'ends_at' => ['nullable','date','after_or_equal:starts_at'],
            'location' => ['nullable','string','max:255'],
            'status' => ['nullable','string','max:50'],
        ]);
        WOServiceAppointment::create($data);
        return back()->with('status', 'Appointment scheduled');
    }

    public function destroy(int $id): RedirectResponse
    {
        $appt = WOServiceAppointment::findOrFail($id);
        $appt->delete();
        return back()->with('status', 'Appointment removed');
    }
}
