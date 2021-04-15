<?php

declare(strict_types=1);

namespace Tipoff\Products\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;
use Tipoff\Locations\Services\LocationRouter;
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

    public function addToCart(AddToCartRequest $request, Product $product)
    {
        /** @var CartInterface $service */
        $service = findService(CartInterface::class);
        throw_unless($service, CartNotAvailableException::class);

        $service::activeCart($request->getEmailAddressId())->upsertItem(
            $product->createCartItem((int) ($request->quantity ?? 1))
        );

        // TODO - should CartInterface provide this answer?
        if (Route::has('checkout.cart-show')) {
            return redirect(\route('checkout.cart-show'));
        }

        return redirect(LocationRouter::build('products'));
    }
}
