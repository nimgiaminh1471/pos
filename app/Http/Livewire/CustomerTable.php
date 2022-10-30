<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Illuminate\Database\Eloquent\Builder;

class CustomerTable extends DataTableComponent
{
    protected $model = Customer::class;

    protected $listeners = ['refresh_customer' => 'refresh'];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSingleSortingDisabled()
            ->setSecondaryHeaderTrAttributes(function($rows) {
                return ['class' => 'bg-gray-100'];
            })
            ->setHideBulkActionsWhenEmptyEnabled();
        $this->setDefaultSort('id', 'desc');
        $this->setUseHeaderAsFooterStatus(false);
        $this->setPerPageAccepted([100, 200, 300]);
        $this->setRefreshMethod('refresh');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Tên khách hàng", "name")
                ->sortable()
                ->searchable(),
            Column::make("Số điện thoại", "phone")
                ->sortable()
                ->searchable(),
            Column::make("Lưu ý", "note"),
            // Column::make("Số lượng sản phẩm", "id")
            //     ->format(
            //         function($value, $row, Column $column) {
            //             $order = Order::find($row->id);
            //             $order_detail = $order->order_detail;
            //             $count = 0;
            //             foreach($order_detail as $item){
            //                 $count += $item->quantity;
            //             }
            //             return $count;
            //         }    
            //     )
            //     ->html(),
            // Column::make("Phương thức thanh toán", 'payment_method')
            //     ->sortable()
            //     ->searchable()
            //     ->format(fn ($value, $row, Column $column) => getMethodName($row->payment_method)),
            // Column::make("Ngày mua hàng", "created_at")
            //     ->sortable(),
            Column::make("Hành động", 'id')
                ->format(
                    fn($value, $row, Column $column) => '
                    <button wire:click="edit(' . $row->id .')"
                                class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">Chỉnh sửa</button>
                            <button wire:click="delete(' . $row->id .')"
                            class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">Xóa</button>'
                )
                ->html(),
        ];
    }

    public function filters(): array
    {
        return [
            // MultiSelectFilter::make('Phương thức thanh toán', 'payment_method')
            //     ->setFilterPillTitle('Phương thức')
            //     ->options([
            //         'momo' => 'Momo',
            //         'transfer' => 'Chuyển khoản',
            //         'cash'  => 'Tiền mặt',
            //     ])
            //     ->filter(function(Builder $builder, array $values) {
            //         $builder->whereIn('payment_method', $values);
            //     }),
            // DateFilter::make('Ngày mua hàng', 'created_at')
            //     ->filter(function (Builder $builder, string $value) {
            //         $builder->where('created_at', '>=', $value);
            //     }),
        ];
    }

    // public function delete(){
    //     // Order::whereIn('id', $this->getSelected())->delete();
    // }

    public function edit($id)
    {
        $this->emit('showEdit', $id);
    }

    public function delete($id)
    {
        $this->emit('delete_customer', $id);
    }

    public function refresh(){

    }
}
