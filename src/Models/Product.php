<?php

declare(strict_types=1);

namespace Tipoff\Products\Models;

use Tipoff\Support\Contracts\Checkout\CartInterface;
use Tipoff\Support\Contracts\Checkout\CartItemInterface;
use Tipoff\Support\Contracts\Sellable\Product as ProductInterface;
use Tipoff\Support\Models\BaseModel;
use Tipoff\Support\Traits\HasCreator;
use Tipoff\Support\Traits\HasPackageFactory;

/**
 * @property int id
 * @property string slug
 * @property string sku
 * @property string title
 * @property int amount
 * @property string tax_code
 * @property int location_id
 * @property int creator_id
 */
class Product extends BaseModel implements ProductInterface
{
    use HasPackageFactory;
    use HasCreator;

    public function getViewComponent($context = null)
    {
        return 'product';
    }

    public function getDescription(): string
    {
        return $this->title;
    }

    public function createCartItem(int $quantity = 1): ?CartItemInterface
    {
        /** @var CartInterface $service */
        $service = findService(CartInterface::class);
        if ($service) {
            return $service::createItem($this, $this->sku, $this->amount, $quantity)
                ->setLocationId($this->location_id)
                ->setTaxCode($this->tax_code);
        }

        return null;
    }
}
