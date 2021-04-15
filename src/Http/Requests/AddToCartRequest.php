<?php

declare(strict_types=1);

namespace Tipoff\Products\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Tipoff\Authorization\Models\User;

class AddToCartRequest extends FormRequest
{
    public function rules()
    {
        return [
            'id' => 'required|exists:products',
            'quantity' => 'nullable|integer|min:1',
        ];
    }

    public function getEmailAddressId(): ?int
    {
        if (Auth::guard('email')->check()) {
            return (int) Auth::guard('email')->id();
        }

        if (Auth::guard('web')->check()) {
            /** @var User $user */
            $user = Auth::guard('web')->user();

            $emailAddress = $user->getPrimaryEmailAddress();

            return $emailAddress ? $emailAddress->id : null;
        }

        return null;
    }
}
