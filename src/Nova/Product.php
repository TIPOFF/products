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

    public static $group = 'Ecommerce Items';

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
            Text::make('SKU (Internal)', 'sku')->rules('required')->creationRules('unique:products,sku')->updateRules('unique:products,sku,{{resourceId}}'),
            Text::make('Title (What Customers See)', 'title')->rules('required'),
            Slug::make('Slug')->from('Title')->rules('required')->creationRules('unique:products,slug')->updateRules('unique:products,slug,{{resourceId}}'),
            Number::make('Amount')->rules(['required', 'max:10'])->sortable(),
            Text::make('Tax Code', 'tax_code'),

            nova('location') ? BelongsTo::make('Location', 'location', nova('location'))->nullable() : null,

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
