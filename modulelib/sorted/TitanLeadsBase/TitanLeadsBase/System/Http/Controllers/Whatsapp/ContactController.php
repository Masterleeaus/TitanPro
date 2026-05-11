<?php

namespace App\Extensions\TitanLeads\System\Http\Controllers\Whatsapp;

use App\Extensions\TitanLeads\System\Http\Requests\ContactRequest;
use App\Extensions\TitanLeads\System\Models\Whatsapp\Contact;
use App\Helpers\Classes\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index()
    {
        return view('titan-leads::contact.index', [
            'title' => trans('Contact Lists'),
            'items' => Contact::query()->where('user_id', Auth::id())->get(),
        ]);
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        if (Helper::appIsDemo()) {
            return back()->with([
                'status'  => 'error',
                'message' => trans('This feature is disabled in demo mode.'),
            ]);
        }

        Contact::query()->create($request->validated());

        return back()->with([
            'status'  => 'success',
            'message' => __('Contact created successfully'),
        ]);
    }

    public function edit(Contact $contact)
    {
        $this->authorize('edit', $contact);

        return view('titan-leads::contact.edit', [
            'item'  => $contact,
            'title' => trans('Edit Contact List'),
        ]);
    }

    public function update(ContactRequest $request, Contact $contact): RedirectResponse
    {
        $this->authorize('update', $contact);

        if (Helper::appIsDemo()) {
            return back()->with([
                'status'  => 'error',
                'message' => trans('This feature is disabled in demo mode.'),
            ]);
        }

        $contact->update($request->validated());

        return back()->with([
            'status'  => 'success',
            'message' => __('Contact updated successfully'),
            'type'    => 'success',
        ]);
    }

    public function destroy(Contact $contact): JsonResponse
    {
        $this->authorize('delete', $contact);

        if (Helper::appIsDemo()) {
            return response()->json([
                'status'  => 'error',
                'message' => trans('This feature is disabled in demo mode.'),
            ]);
        }

        $contact->delete();

        return response()->json([
            'status'  => 'success',
            'message' => __('Contact deleted successfully'),
        ]);
    }
}
