<?php

namespace App\Extensions\TitanOperator\System\Http\Controllers;

use App\Extensions\TitanOperator\System\Http\Requests\TitanOperatorCustomerRequest;
use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Models\TitanOperatorCustomer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;

class TitanOperatorCustomerController extends Controller
{
    public function index()
    {
        return view('titan_operator::contact.index', [
            'items' => TitanOperatorCustomer::query()
                ->where('user_id', auth()->id())
                ->paginate(20),
            'title'       => __('Contacts'),
            'description' => __('Manage your titan_operator contacts and view their interaction history.'),
        ]);
    }

    public function edit(TitanOperatorCustomer $operatorCustomer)
    {
        return view('titan_operator::contact.edit', [
            'item'        => $operatorCustomer,
            'action'      => route('dashboard.titan_operator.titan_operator-customer.update', $operatorCustomer->getKey()),
            'method'      => 'PUT',
            'operators'    => TitanOperator::query()->where('user_id', auth()->id())->get(),
            'title'       => __('Edit Contact'),
            'description' => __('Edit Contact.'),
        ]);
    }

    public function update(TitanOperatorCustomerRequest $request, TitanOperatorCustomer $operatorCustomer): RedirectResponse
    {
        $operatorCustomer->update($request->validated());

        return redirect()->route('dashboard.titan_operator.titan_operator-customer.index')
            ->with([
                'type'    => 'success',
                'message' => __('Contact updated.'),
            ]);
    }

    public function destroy(TitanOperatorCustomer $operatorCustomer): RedirectResponse
    {
        $operatorCustomer->delete();

        return redirect()->route('dashboard.titan_operator.titan_operator-customer.index')
            ->with([
                'type'    => 'success',
                'message' => __('Knowledge base article successfully deleted.'),
            ]);
    }
}
