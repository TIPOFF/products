<?php

declare(strict_types=1);

namespace Tipoff\Products\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Tipoff\Authorization\Traits\UsesTipoffAuthentication;

class AddToCartRequest extends FormRequest
{
    use UsesTipoffAuthentication;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required|exists:products',
            'quantity' => 'nullable|integer|min:1',
        ];
    }
}
