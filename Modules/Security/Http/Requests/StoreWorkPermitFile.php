<?php

namespace Modules\Security\Http\Requests;

use App\Http\Requests\CoreRequest;

class StoreWorkPermitFile extends CoreRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'wp_id' => ['required', 'integer', 'min:1', 'exists:workpermits,id'],
            'file' => ['required'],
            'file.*' => ['file', 'max:' . (int) config('security.uploads.max_kb', 5120)],
        ];
    }
}
