<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Order;
use App\Models\Product;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Illuminate\Database\Eloquent\Builder;

class OrderTable extends DataTableComponent
{
    protected $model = Order::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSingleSortingDisabled()
            ->setSecondaryHeaderTrAttributes(function($rows) {
                return ['class' => 'bg-gray-100'];
            })
            ->setBulkActions([
                'delete' => 'Xóa',
            ])
            ->setUseHeaderAsFooterEnabled()
            ->setHideBulkActionsWhenEmptyEnabled();
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Tổng đơn hàng", "total")
                ->sortable()
                ->searchable()
                ->format(fn ($value, $row, Column $column) => number_format($row->total, 0, ',', '.')),
            Column::make("Phương thức thanh toán", 'payment_method')
                ->sortable()
                ->searchable()
                ->format(fn ($value, $row, Column $column) => getMethodName($row->payment_method)),
            Column::make("Ngày mua hàng", "created_at")
                ->sortable()
        ];
    }

    public function filters(): array
    {
        return [
            MultiSelectFilter::make('Phương thức thanh toán', 'payment_method')
                ->setFilterPillTitle('Phương thức')
                ->options([
                    'momo' => 'Momo',
                    'transfer' => 'Chuyển khoản',
                    'cash'  => 'Tiền mặt',
                ])
                ->filter(function(Builder $builder, array $values) {
                    $builder->whereIn('payment_method', $values);
                }),
            DateFilter::make('Ngày mua hàng', 'created_at')
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('created_at', '>=', $value);
                }),
        ];
    }

    public function delete(){
        Product::whereIn('id', $this->getSelected())->delete();
    }
}
