<x-slot name="header">
    <h2 class="text-center">Sản phẩm</h2>
</x-slot>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
            @if (session()->has('message'))
            <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3"
                role="alert">
                <div class="flex">
                    <div>
                        <p class="text-sm">{{ session('message') }}</p>
                    </div>
                </div>
            </div>
            @endif
            <button wire:click="create()"
                class="my-4 inline-flex justify-center w-100 rounded-md border border-transparent px-4 py-2 bg-red-600 text-base font-bold text-white shadow-sm hover:bg-red-700">
                Thêm sản phẩm
            </button>
            @if($isModalOpen)
            @include('livewire.product-create')
            @endif
            <table class="table-fixed w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 w-20">Số ID</th>
                        <th class="px-4 py-2">Tên sản phẩm</th>
                        <th class="px-4 py-2">Hình ảnh</th>
                        <th class="px-4 py-2">Giá</th>
                        <th class="px-4 py-2">Loại doanh thu</th>
                        <th class="px-4 py-2">Hành động</th>
                    </tr>
                </thead>
                <tbody wire:sortable="updateProductOrder" wire:sortable.options="{ animation: 100 }">
                    @foreach($products as $product)
                    <tr wire:sortable.item="{{ $product->id }}" wire:key="product-{{ $product->id }}">
                        <td class="border px-4 py-2">{{ $product->id }}</td>
                        <td class="border px-4 py-2">{{ $product->name }}</td>
                        <td class="border px-4 py-2"><image width="100" src="{{ asset('storage/' .$product->image) }}"></td>
                        <td class="border px-4 py-2">{{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="border px-4 py-2">{{ $product->revenue_type == 'normal' ? 'Bình thường' : 'Đặc biệt' }}</td>
                        <td class="border px-4 py-2">
                            <button wire:click="edit({{ $product->id }})"
                                class="flex px-4 py-2 bg-gray-500 text-gray-900 cursor-pointer">Chỉnh sửa</button>
                            <button wire:click="delete({{ $product->id }})"
                                class="flex px-4 py-2 bg-red-100 text-gray-900 cursor-pointer">Xóa</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>