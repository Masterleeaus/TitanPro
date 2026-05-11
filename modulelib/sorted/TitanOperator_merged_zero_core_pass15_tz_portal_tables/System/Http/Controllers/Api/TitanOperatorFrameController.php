<?php

namespace App\Extensions\TitanOperator\System\Http\Controllers\Api;

use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Models\TitanOperatorConversation;
use App\Extensions\TitanOperator\System\Models\TitanOperatorCustomer;
use App\Helpers\Classes\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class TitanOperatorFrameController extends Controller
{
    public function frame(Request $request, TitanOperator $titan_operator): View
    {
        $session = $this->getVisitor();

        $customer = $this->createCustomer($titan_operator, $session);

        $customerId = $customer->getKey();

        $conversations = TitanOperatorConversation::query()
            ->where('operator_id', $titan_operator->getAttribute('id'))
            ->where('session_id', $session)
            ->get();

        $titan_operator->setAttribute('enabled_sound', $customer->getAttribute('enabled_sound'));

        $this->updateTitanOperatorConversation($conversations, $customerId);

        return view('titan_operator::frame', compact('titan_operator', 'session', 'conversations'));
    }

    public function updateTitanOperatorConversation(Collection $conversations, $customerId): void
    {
        if ($conversations->whereNull('operator_customer_id')?->count()) {
            TitanOperatorConversation::query()
                ->whereIn(
                    'id',
                    $conversations->whereNull('operator_customer_id')->pluck('id')->toArray()
                )
                ->update([
                    'operator_customer_id' => $customerId,
                ]);
        }
    }

    public function createCustomer(TitanOperator $titan_operator, string $session)
    {
        $customer = TitanOperatorCustomer::query()->firstOrCreate([
            'user_id'         => $titan_operator->getAttribute('user_id'),
            'operator_id'      => $titan_operator->getAttribute('id'),
            'session_id'      => $session,
            'operator_channel' => 'frame',
        ], [
            'name'            => 'Anonymous User',
            'ip_address'      => Helper::getRequestIp(),
            'country_code'    => Helper::getRequestCountryCode(),
            'enabled_sound'   => true,
        ]);

        $customer->update([
            'ip_address'      => Helper::getRequestIp(),
            'country_code'    => Helper::getRequestCountryCode(),
        ]);

        return $customer;
    }

    protected function getVisitor(): string
    {
        $cookie = Cookie::has('TITAN_OPERATOR_VISITOR');

        if ($cookie) {
            return Cookie::get('TITAN_OPERATOR_VISITOR');
        }

        $sessionId = md5(uniqid(mt_rand(), true));

        Cookie::queue('TITAN_OPERATOR_VISITOR', $sessionId, 60 * 24 * 365);

        return $sessionId;
    }
}
