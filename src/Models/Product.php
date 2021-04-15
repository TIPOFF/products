<?php

declare(strict_types=1);

namespace Tipoff\Products\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Tipoff\Authorization\Models\User;
use Tipoff\Locations\Models\Location;
use Tipoff\Products\Exceptions\CartNotAvailableException;
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
 * @property Location location
 * @property User creator
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property int location_id
 * @property int creator_id
 */
class Product extends BaseModel implements ProductInterface
{
    use HasPackageFactory;
    use HasCreator;

    protected $casts = [
        'id' => 'integer',
        'amount' => 'integer',
        'location_id' => 'integer',
        'creator_id' => 'integer',
        'updater_id' => 'integer',
    ];

    public function scopeByLocation(Builder $query, ?int $locationId): Builder
    {
        return $query->where(function (Builder $q) use ($locationId) {
            $q->whereNull('location_id');
            if ($locationId) {
                $q->orWhere('location_id', '=', $locationId);
            }
        });
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function getViewComponent($context = null)
    {
        return implode('-', ['tipoff', 'product', $context]);
    }

    public function getDescription(): string
    {
        return $this->title;
    }

    public function createCartItem(int $quantity = 1): CartItemInterface
    {
        /** @var CartInterface $service */
        $service = findService(CartInterface::class);
        throw_unless($service, CartNotAvailableException::class);

        return $service::createItem($this, $this->sku, $this->amount, $quantity)
            ->setLocationId($this->location_id)
            ->setTaxCode($this->tax_code);
    }
}
