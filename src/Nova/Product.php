<?php

declare(strict_types=1);

namespace Tipoff\Products\Nova;

use \Tipoff\Products\Models\Product as ProductModel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Tipoff\Support\Nova\BaseResource;

class Product extends BaseResource
{
    public static $model = ProductModel::class;

    public static $title = 'sku';

    public static $search = [
        'id',
        'sku',
        'title',
    ];

    public static $group = 'Operations Units';

    public function fieldsForIndex(NovaRequest $request)
    {
        return array_filter([
            ID::make(),
            Text::make('Sku')->sortable(),
            Number::make('Amount')->sortable(),
        ]);
    }

    public function fields(Request $request)
    {
        return array_filter([
            Text::make('SKU (Internal)', 'sku')->required(),
            Text::make('Title (What Customers See)', 'title'),
            Slug::make('Slug')->from('Title'),
            Number::make('Amount')->sortable(),
            Text::make('Tax Code', 'tax_code'),

            nova('location') ? BelongsTo::make('Location', 'location', nova('location')) : null,

            new Panel('Data Fields', $this->dataFields()),
        ]);
    }

    protected function dataFields(): array
    {
        return array_merge(
            parent::dataFields(),
            $this->creatorDataFields()
        );
    }
}
