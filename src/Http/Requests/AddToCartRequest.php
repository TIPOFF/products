<?php

declare(strict_types=1);

namespace Tipoff\Products\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Tipoff\Authorization\Traits\UsesTipoffAuthentication;

class AddToCartRequest extends FormRequest
{
    use UsesTipoffAuthentication;

    public function rules()
    {
        return [
            'quantity' => 'nullable|integer|min:1',
        ];
    }
}
