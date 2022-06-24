<x-app-layout>
    <x-slot name="header">
        <h2 class="text-center">Thống kê</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <canvas id="customerChart" height="100px"></canvas>
            </div>
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <canvas id="productChart" height="100px"></canvas>
            </div>
        </div>
    </div>
    <script>

        var dynamicColors = function() {
            var r = Math.floor(Math.random() * 255);
            var g = Math.floor(Math.random() * 255);
            var b = Math.floor(Math.random() * 255);
            return "rgb(" + r + "," + g + "," + b + ")";
        };

        var labels = {{ Js::from($customer['label']) }};

        var users = {{ Js::from($customer['data']) }};

        const data = {
            labels: labels,
            datasets: [{
                label: 'Top 10 Khách hàng',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: users,
                backgroundColor: []
            }]
        };

        for(i = 0; i < users.length ; i++){
            data.datasets[0].backgroundColor.push(dynamicColors());
        }

        const config = {
            type: 'bar',
            data: data,
            options: {}
        };

        const customerChart = new Chart(
            document.getElementById('customerChart'),
            config
        );

        var label_product = {{ Js::from($product['label']) }};

        var products = {{ Js::from($product['data']) }};

        const dataProduct = {
            labels: label_product,
            datasets: [{
                label: 'Top 10 Sản phẩm trong tháng',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: products,
                backgroundColor: []
            }]
        };

        for(i = 0; i < products.length ; i++){
            dataProduct.datasets[0].backgroundColor.push(dynamicColors());
        }

        const configProduct = {
            type: 'bar',
            data: dataProduct,
            options: {}
        };

        const productChart = new Chart(
            document.getElementById('productChart'),
            configProduct
        );

    </script>
</x-app-layout>