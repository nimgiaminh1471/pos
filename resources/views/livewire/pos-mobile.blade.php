<div class="flex flex-grow flex-col h-screen">
    <!-- store menu -->
    <div class="flex flex-col bg-blue-gray-50 h-full py-4">
        <div class="h-full overflow-hidden mt-4">
            <div class="h-full overflow-y-auto px-2">
                @if(count($products) <= 0) 
                <div class="select-none bg-blue-gray-100 rounded-3xl flex flex-wrap content-center justify-center h-full opacity-25">
                    <div class="w-full text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                        </svg>
                        <p class="text-xl">
                            YOU DON'T HAVE
                            <br />
                            ANY PRODUCTS TO SHOW
                        </p>
                    </div>
                </div>
                @else
                <div class="grid grid-cols-3 gap-3 pb-3">
                    @foreach($products as $product)
                    <div wire:click="addToCart({{ $product->id }})"  class="select-none cursor-pointer transition-shadow overflow-hidden rounded-2xl bg-white shadow hover:shadow-lg" wire:key="product_{{ $product->id }}" >
                        <img src="{{ asset('storage/' .$product->image) }}">
                        <div class="flex pb-3 px-3 text-sm -mt-3">
                            <p class="flex-grow truncate mr-1">{{ $product->name }}</p>
                            <p class="nowrap font-semibold">{{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
    <!-- right sidebar -->
    <div class="flex flex-col bg-blue-gray-50 h-full bg-white pr-4 pl-2 py-4">
        <div class="bg-white rounded-3xl flex flex-col h-full shadow">
            <!-- empty cart -->
            @if(count($cart) <= 0) 
            <div class="flex-1 w-full p-4 opacity-25 select-none flex flex-col flex-wrap content-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p>
                    CART EMPTY
                </p>
            </div>
            @else
            <!-- cart items -->
            <div class="flex-1 flex flex-col overflow-auto">
                <div class="h-16 text-center flex justify-center">
                    <div class="pl-8 text-left text-lg py-4 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <div class="text-center absolute bg-cyan-500 text-white w-5 h-5 text-xs p-0 leading-5 rounded-full -right-2 top-3">{{ count($cart) }}</div>
                    </div>
                    <div class="flex-grow px-8 text-right text-lg py-4 relative">
                        <button x-on:click="clear()" class="text-blue-gray-300 hover:text-pink-500 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex-1 w-full px-4 overflow-auto">
                    @foreach($cart as $key => $item)
                        <div wire:key="cart_{{ $loop->index }}" class="select-none mb-3 bg-blue-gray-50 rounded-lg w-full text-blue-gray-700 py-2 px-2 flex justify-center">
                            <img src="{{ asset('storage/' .$item['image']) }}" alt="" class="rounded-lg h-10 w-10 bg-white shadow mr-2">
                            <div class="flex-grow">
                                <h5 class="text-sm">{{ $item['name'] }}</h5>
                                <p class="text-xs block">{{ number_format($item['price'], 0, ',', '.') }}</p>
                            </div>
                            <div class="py-1">
                                <div class="w-40 grid grid-cols-3 gap-2 ml-2">
                                    <button wire:click="addQty({{ $key }}, '-1')" class="rounded-lg text-center py-1 text-white bg-blue-gray-600 hover:bg-blue-gray-700 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-3 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                        </svg>
                                    </button>
                                    <input type="text" value="{{ $item['quantity'] }}" class="bg-white rounded-lg text-center shadow focus:outline-none focus:shadow-lg text-sm">
                                    <button wire:click="addQty({{ $key }}, '+1')" class="rounded-lg text-center py-1 text-white bg-blue-gray-600 hover:bg-blue-gray-700 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-3 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- end of cart items -->
            @endif

            <!-- payment info -->
            <div class="select-none h-auto w-full text-center pt-3 pb-4 px-4">
                <div class="flex mb-3 text-lg font-semibold text-blue-gray-700">
                    <div>TOTAL</div>
                    <div class="text-right w-full">{{ number_format($total, 0, ',', '.') }}</div>
                </div>
                <div class="flex flex-row">
                    <div class="basis-1/3">
                        <button wire:click="payment('momo')" class="text-white rounded-2xl text-lg w-full py-3 focus:outline-none bg-red-500 hover:bg-red-600">
                            MOMO
                        </button>
                    </div>
                    <div class="basis-1/3">
                        <button wire:click="payment('transfer')" class="text-white rounded-2xl text-lg w-full py-3 focus:outline-none bg-cyan-500 hover:bg-cyan-600">
                            BANKING
                        </button>
                    </div>
                    <div class="basis-1/3">
                        <button wire:click="payment('cash')" class="text-white rounded-2xl text-lg w-full py-3 focus:outline-none bg-green-500 hover:bg-green-600">
                            TIỀN MẶT
                        </button>
                    </div>
                </div>
            </div>
            <!-- end of payment info -->
        </div>
    </div>
    <!-- end of right sidebar -->
</div>