<x-app-layout>
    <x-slot name="header">
        <h2 class="text-center">Thống kê</h2>
    </x-slot>
    <div class="pt-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <form action="">
                    <input class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="month" min="2018-03" name="date" value="{{ $date }}" />
                    <button type="submit" class="inline-flex justify-center rounded-md border border-transparent px-4 py-2 bg-red-600 text-base leading-6 font-bold text-white shadow-sm hover:bg-red-700 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                        Xem báo cáo
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="pt-8 max-w-7xl mx-auto sm:px-6 lg:px-8 flex-grow flex">
        <div class="w-full flex-col px-1">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <div class="bg-gradient-to-b from-green-200 to-green-100 border-b-4 border-green-600 rounded-lg shadow-xl p-5">
                    <div class="flex flex-row items-center">
                        <div class="flex-1 text-right md:text-center">
                            <h5 class="font-bold uppercase text-gray-400">Tổng thu nhập tháng</h5>
                            <h3 class="font-bold text-3xl text-gray-600">{{ number_format($total) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full flex-col px-1">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <div class="bg-gradient-to-b from-pink-200 to-pink-100 border-b-4 border-pink-500 rounded-lg shadow-xl p-5">
                    <div class="flex flex-row items-center">
                        <div class="flex-1 text-right md:text-center">
                            <h5 class="font-bold uppercase text-gray-400">Tổng SL sản phẩm</h5>
                            <h3 class="font-bold text-3xl text-gray-600">{{ $total_products }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full flex-col px-1">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <div class="bg-gradient-to-b from-yellow-200 to-yellow-100 border-b-4 border-yellow-600 rounded-lg shadow-xl p-5">
                    <div class="flex flex-row items-center">
                        <div class="flex-1 text-right md:text-center">
                            <h5 class="font-bold uppercase text-gray-400">Khách hàng mới</h5>
                            <h3 class="font-bold text-3xl text-gray-600">{{ $total_customers }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full flex-col px-1">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <div class="bg-gradient-to-b from-indigo-200 to-indigo-100 border-b-4 border-indigo-500 rounded-lg shadow-xl p-5">
                    <div class="flex flex-row items-center">
                        <div class="flex-1 text-right md:text-center">
                            <h5 class="font-bold uppercase text-gray-400">Doanh thu đặc biệt</h5>
                            <h3 class="font-bold text-3xl text-gray-600">{{ number_format($total_special_revenue) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pt-8 max-w-7xl mx-auto sm:px-6 lg:px-8 flex-grow flex">
        <div class="w-1/2 flex-col px-1">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <h2 class="text-center">CHART ORDER BY MONTHS</h2>
                <canvas id="customerChart"></canvas>
            </div>
        </div>
        <div class="w-1/2 flex-col px-1">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <h2 class="text-center">CHART ORDER BY DAY</h2>
                <canvas id="customerChartDay"></canvas>
            </div>
        </div>
    </div>

    <div class="pt-8 max-w-7xl mx-auto sm:px-6 lg:px-8 flex-grow flex">
        <div class="w-1/2 flex-col px-1">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <h2 class="text-center">BEST SELL</h2>
                <table class="table-fixed w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2">Tên sản phẩm</th>
                            <th class="px-4 py-2">Tổng bán</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($topProduct)
                        @foreach($topProduct as $product)
                        <tr>
                            <td class="border px-4 py-2">{{ $product['product']['name'] }}</td>
                            <td class="border px-4 py-2">{{ $product['total'] }}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="w-1/2 flex-col px-1">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <h2 class="text-center">BEST CUSTOMER</h2>
                <table class="table-fixed w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2">Tên khách hàng</th>
                            <th class="px-4 py-2">Tổng mua</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($topCustomer)
                        @foreach($topCustomer as $customer)
                        <tr>
                            <td class="border px-4 py-2">{{ $customer['customer']['name'] }}</td>
                            <td class="border px-4 py-2">{{ number_format($customer['purchase_total']) }}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <x-slot name="script">
        <script>
            let customerChartMonthY = [{!! implode(',', array_values($dataMonths)) !!}];
            let customerChartMonthX = [{!! "'". implode("','", array_keys($dataMonths)) . "'" !!}];
        </script>
        <script>
            var dynamicColors = function() {
                var r = Math.floor(Math.random() * 255);
                var g = Math.floor(Math.random() * 255);
                var b = Math.floor(Math.random() * 255);
                return "rgb(" + r + "," + g + "," + b + ")";
            };
        </script>
        <script>
            var xValues = customerChartMonthX;
            var yValues = customerChartMonthY;

            var barColors = [];
            for (var i in yValues) {
                barColors.push(dynamicColors());
            }
            const customerChart = new Chart("customerChart", {
                type: "bar",
                data: {
                    labels: xValues,
                    datasets: [{
                        backgroundColor: barColors,
                        data: yValues,
                        label: 'Order',
                    }]
                },
            });
        </script>
        <script>
            let customerChartDayY = [{!! implode(',', array_values($dataDays)) !!}];
            let customerChartDayX = [{!! "'". implode("','", array_keys($dataDays)) . "'" !!}];
        </script>
        <script>
            var xValues = customerChartDayX;
            var yValues = customerChartDayY;

            var barColors = [];
            for (var i in yValues) {
                barColors.push(dynamicColors());
            }
            const customerChartDay = new Chart("customerChartDay", {
                type: "bar",
                data: {
                    labels: xValues,
                    datasets: [{
                        backgroundColor: barColors,
                        data: yValues,
                        label: 'Order',
                    }]
                },
            });
        </script>
    </x-slot>
</x-app-layout>