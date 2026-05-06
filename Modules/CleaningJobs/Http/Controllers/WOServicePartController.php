<?php

namespace Modules\CleaningJobs\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\CleaningJobs\Models\WOServicePart;
use Modules\CleaningJobs\Services\WorkOrderTotals;

class WOServicePartController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'work_order_id' => ['required', 'integer'],
            'service_part_id' => ['nullable', 'integer'],
            'quantity' => ['nullable', 'numeric', 'min:0'],
            'qty' => ['nullable', 'numeric', 'min:0'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'type' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ]);

        $data['qty'] = $data['qty'] ?? $data['quantity'] ?? 1;
        $data['price'] = $data['price'] ?? $data['amount'] ?? 0;
        $data['quantity'] = $data['quantity'] ?? $data['qty'];
        $data['amount'] = $data['amount'] ?? $data['price'];
        $data['total'] = $data['qty'] * $data['price'];

        $item = WOServicePart::create($data);
        WorkOrderTotals::recalc((int) $item->work_order_id);

        return back()->with('status', 'Service part added');
    }

    public function destroy(int $id): RedirectResponse
    {
        $item = WOServicePart::findOrFail($id);
        $workOrderId = (int) $item->work_order_id;
        $item->delete();
        WorkOrderTotals::recalc($workOrderId);

        return back()->with('status', 'Service part removed');
    }
}
