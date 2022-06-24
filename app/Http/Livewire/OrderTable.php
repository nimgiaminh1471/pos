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
                'delete' => 'Xóa'
            ])
            ->setHideBulkActionsWhenEmptyEnabled();
        $this->setDefaultSort('id', 'desc');
        $this->setUseHeaderAsFooterStatus(false);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Tổng đơn hàng", "total")
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, Column $column){
                    $order = Order::find($row->id);
                    $count = $row->total;
                    if($order->discount_value > 0){
                        if($order->discount_type == "percent"){
                            $count = round($count * (1 - $order->discount_value / 100), 0);
                        }else{
                            $count = round($count - $order->discount_value, 0);
                        }
                    }
                    return number_format($count, 0, ',', '.');
                })
                ->html()
                ->footer(function($rows) {
                    $count = 0;
                    foreach($rows as $row){
                        $order_count = 0;
                        $order = Order::find($row->id);
                        $order_count = $order->total;
                        if($order->discount_type == "percent"){
                            $order_count = round($order_count * (1 - $order->discount_value / 100), 0);
                        }else{
                            $order_count = round($order_count - $order->discount_value, 0);
                        }
                        $count+=$order_count;
                    }
                    return 'Tổng tiền: ' . number_format($count, 0, ',', '.');
                }),
            Column::make("Số lượng sản phẩm", "id")
                ->format(
                    function($value, $row, Column $column) {
                        $order = Order::find($row->id);
                        $order_detail = $order->order_detail;
                        $count = 0;
                        foreach($order_detail as $item){
                            $count += $item->quantity;
                        }
                        return $count;
                    }    
                )
                ->html()
                ->footer(function($rows) {
                    $count = 0;
                    foreach($rows as $row){
                        $order = Order::find($row->id);
                        $order_detail = $order->order_detail;
                        foreach($order_detail as $item){
                            $count += $item->quantity;
                        }
                    }
                    return 'Tổng số lượng sản phẩm: ' . $count;
                }),
            Column::make("Phương thức thanh toán", 'payment_method')
                ->sortable()
                ->searchable()
                ->format(fn ($value, $row, Column $column) => getMethodName($row->payment_method)),
            Column::make("Ngày mua hàng", "created_at")
                ->sortable(),
            Column::make("In hóa đơn", 'id')
                ->format(
                    fn($value, $row, Column $column) => '<a class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600" href="'. route('printOrder', ['id' => $row->id]) .'">In hóa đơn</a>'
                )
                ->html(),
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
        Order::whereIn('id', $this->getSelected())->delete();
    }

}
