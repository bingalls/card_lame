<?php

namespace App\Http\Requests\Frontend\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateProfileRequest.
 */
class CardPlayRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    final public function rules(): array
    {
        return [
            'cards' => ['required', 'regex:/^([jqkaJQKA2-9]|(10))( ([jqkaJQKA2-9]|(10))){0,12}$/'],
        ];
    }
}
