<?php

declare(strict_types=1);

namespace Tipoff\Products\Http\Controllers;

use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;
use Tipoff\Products\Exceptions\CartNotAvailableException;
use Tipoff\Products\Http\Requests\AddToCartRequest;
use Tipoff\Products\Models\Product;
use Tipoff\Support\Contracts\Checkout\CartInterface;
use Tipoff\Support\Http\Controllers\BaseController;

class ProductsController extends BaseController
{
    public function index(Market $market, Location $location)
    {
        $products = Product::query()->byLocation($location->id)->get();

        return view('products::products')->with([
            'market' => $market,
            'location' => $location,
            'products' => $products,
            'hasCart' => app()->has(CartInterface::class),
        ]);
    }

    public function addToCart(AddToCartRequest $request)
    {
        /** @var CartInterface $service */
        $service = findService(CartInterface::class);
        throw_unless($service, CartNotAvailableException::class);

        $product = Product::query()->findOrFail($request->id);
        $service::queuedUpsertItem(
            $product->createCartItem($request->quantity ?? 1),
            $request->getEmailAddressId()
        );

        return redirect($service::route('checkout.cart-show'));
    }
}
