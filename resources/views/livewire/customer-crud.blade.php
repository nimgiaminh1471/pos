<x-slot name="header">
    <h2 class="text-center">Khách hàng</h2>
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
                Thêm khách hàng
            </button>
            @if($isModalOpen)
            @include('livewire.customer-create')
            @endif
            <livewire:customer-table />
            <!-- <table class="table-fixed w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 w-20">Số ID</th>
                        <th class="px-4 py-2">Tên khách hàng</th>
                        <th class="px-4 py-2">Số điện thoại</th>
                        <th class="px-4 py-2">Lưu ý</th>
                        <th class="px-4 py-2">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    <tr>
                        <td class="border px-4 py-2">{{ $customer->id }}</td>
                        <td class="border px-4 py-2">{{ $customer->name }}</td>
                        <td class="border px-4 py-2">{{ $customer->phone }}</td>
                        <td class="border px-4 py-2">{{ $customer->note }}</td>
                        <td class="border px-4 py-2">
                            <button wire:click="edit({{ $customer->id }})"
                                class="flex px-4 py-2 bg-gray-500 text-gray-900 cursor-pointer">Chỉnh sửa</button>
                            <button wire:click="delete({{ $customer->id }})"
                                class="flex px-4 py-2 bg-red-100 text-gray-900 cursor-pointer">Xóa</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table> -->
        </div>
    </div>
</div>