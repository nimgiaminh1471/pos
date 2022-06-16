<x-slot name="header">
    <h2 class="text-center">Đơn hàng</h2>
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
            Tổng kết: {{ number_format($total, 0, ',', '.') }}

            <table class="table-fixed w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 w-20">Số ID</th>
                        <th class="px-4 py-2">Tổng đơn hàng</th>
                        <th class="px-4 py-2">Hình thức thanh toán</th>
                        <th class="px-4 py-2">Ngày mua hàng</th>
                        <th class="px-4 py-2">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td class="border px-4 py-2">{{ $order->id }}</td>
                        <td class="border px-4 py-2">{{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="border px-4 py-2">{{ getMethodName($order->payment_method) }}</td>
                        <td class="border px-4 py-2">{{ $order->created_at }}</td>
                        <td class="border px-4 py-2">
                            <button wire:click="delete({{ $order->id }})"
                                class="flex px-4 py-2 bg-red-100 text-gray-900 cursor-pointer">Xóa</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $paginate->links() }}
        </div>
    </div>
</div>