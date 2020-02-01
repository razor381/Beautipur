@extends('layout')
@section('content')
<!-- Header -->
<div class="height-15 header-background bg-cover bg-center">
  <div class="h-full flex w-full header-opacity-slight items-end pl-48">
    <div class="mb-16 p-1 text-white font-semibold text-xl flex items-center text-2xl">
      <a href="{{ route('home') }}">Home</a>
      <box-icon color="#ffffff" class="mx-2" name='chevron-right'></box-icon>
      <a>Shopping Cart</a>
    </div>
  </div>
</div>
<!-- Header Ending-->

<div class="container mx-auto py-20">
  <!-- NOTIFICATIONS-->
  @if( session()->has('SUCCESS_MESSAGE'))
  <div class="flex justify-center mt-3">
    <div class="notification bg-green-400">
      {{ session()->get('SUCCESS_MESSAGE')}}
    </div>
  </div>
  @endif

  @if( count($errors) > 0 )
  <div class="flex flex-col items-center mt-3">
    @foreach( $errors->all as $error )
    <div class="notification bg-red-400">
      {{ $error }}
    </div>
    @endforeach
  </div>
  @endif
  <!-- NOTIFICATIONS ending-->
  <div class="w-3/4">
    @if( Cart::count() > 0)
    <div class="font-bold text-xl p-4">{{ Cart::count() }} item(s) in cart</div>
    <div>
      <!-- PRODUCT -->
      @foreach( Cart::content() as $item)
      <div class="flex items-center justify-around h-24 bg-gray-100 py-2 my-1">
        <a class="w-20 h-full border rounded flex items-center justify-center p-1"
          href="{{ route('shop.show', $item->model->slug) }}">
          <img src="{{ asset('images/product.png') }}" class="w-full h-auto">
        </a>
        <div class="font-bold">
          <a href="{{ route('shop.show', $item->model->slug) }}">
            {{ $item->model->name }}
          </a>
          <div class="text-xs text-gray-600">{{ $item->model->details }}</div>
        </div>
        <div class="flex items-center h-full">
          <div class="h-full flex flex-col justify-around items-center">
            <form action="{{ route('cart.destroy', $item->rowId) }}" method="POST">
              {{ csrf_field() }}
              {{ method_field('DELETE') }}
              <button type="submit"
                class="text-xs text-gray-600 cursor-pointer px-3 border border-gray-400
              hover:bg-gray-200 rounded uppercase">remove</button>
            </form>
            <form action="{{ route('cart.saveForLater', $item->rowId) }}" method="POST">
              {{ csrf_field() }}
              <button type="submit"
                class="text-xs text-gray-600 cursor-pointer px-3 border border-gray-400
              hover:bg-gray-200 rounded uppercase">Save for later</button>
            </form>
          </div>
          <div class="flex border rounded-sm items-center mx-8">
            <div class="px-3 h-full">1</div>
            <div class="flex flex-col justify-center items-center bg-teal-400 border border-teal-400">
              <button class="h-5 w-5 hover:bg-teal-500">
                <box-icon size="xs" color="white" name='chevron-up'></box-icon>
              </button>
              <button class="h-5 w-5 hover:bg-teal-500">
                <box-icon size="xs" color="white" name='chevron-down' ></box-icon>
              </button>
            </div>
          </div>
          <div class="text-lg font-bold">{{ $item->model->presentPrice() }}</div>
        </div>
      </div>
      @endforeach
      <!-- PRODUCT ending -->
    </div>

    <!-- CODE INPUT -->
    <div class="py-10">
      <div class="text-lg font-semibold my-2">Have a Code?</div>
      <div class="flex border rounded-sm h-10 w-1/2">
        <input class="flex-1 px-3" type="text" name="codeInput">
        <button class="bg-gray-500 text-white font-bold px-4 hover:bg-gray-600">Apply</button>
      </div>
    </div>
    <!-- CODE INPUT ending-->

    <div class="bg-gray-100 h-32 flex items-center my-6">
      <div class="flex-1 text-center">Delivery free above $100!</div>
      <table class="w-1/3">
        <tr>
          <td>Subtotal</td>
          <td>{{ presentPrice(Cart::subtotal()) }}</td>
        </tr>
        <tr>
          <td class="py-1">Tax (13%)</td>
          <td class="py-1">{{ presentPrice(Cart::tax()) }}</td>
        </tr>
        <tr class="font-bold text-black text-lg">
          <td>Total</td>
          <td>{{ presentPrice(Cart::total()) }}</td>
        </tr>
      </table>
    </div>

    <div class="flex justify-between w-full">
      <a href="{{ route('shop.index') }}"
        class="button-outline hover:bg-gray-200">Continue Shopping</a>
      <button class="button-outline bg-teal-400 hover:bg-teal-500 font-bold text-white">Proceed to Checkout</button>
    </div>
    @else
    <div  class="notification bg-gray-600">No items in cart!</div>
    <a href="{{ route('shop.index') }}" type="submit" class="button-outline hover:bg-gray-200">
      Continue Shopping
    </a>
    @endif
    <!-- SAVED PRODUCTS -->
    <div class="mt-32">
      @if( Cart::instance('savedForLater')->count() > 0)
      <div class="font-bold text-xl p-4">{{ Cart::instance('savedForLater')->count() }} Item(s) Saved For Later</div>
      <div>
        <!-- PRODUCT -->
        @foreach( Cart::instance('savedForLater')->content() as $item )
        <div class="flex items-center justify-around h-24 bg-gray-100 py-2 my-1">
          <a href="{{ route('shop.show', $item->model->slug) }}"
            class="w-20 h-full border rounded flex items-center justify-center p-1">
            <img src="{{ asset('images/product.png') }}" class="w-full h-auto">
          </a>
          <div class="font-bold">
            <a href="{{ route('shop.show', $item->model->slug) }}">
              {{ $item->model->name }}
            </a>
            <div class="text-xs text-gray-600">{{ $item->model->details }}</div>
          </div>
          <div class="flex items-center h-full">
            <div class="flex flex-col justify-around h-full items-center">
              <form action="{{ route('cart.switchToCart', $item->rowId) }}" method="POST"
                class="mr-6">
                {{ csrf_field() }}
                <button type="submit"
                  class="text-xs text-gray-600 cursor-pointer px-3 border border-gray-400
                hover:bg-gray-200 rounded uppercase">Add to cart</button>
              </form>
              <form action="{{ route('cart.destroySavedForLater', $item->rowId) }}" method="POST"
                class="mr-6">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit"
                  class="text-xs text-gray-600 cursor-pointer px-3 border border-gray-400
                hover:bg-gray-200 rounded uppercase">remove</button>
              </form>
            </div>
            <div class="text-lg font-bold">{{ $item->model->presentPrice() }}</div>
          </div>
        </div>
        @endforeach
        <!-- PRODUCT ending -->
      </div>
      @else
      <div  class="notification bg-gray-600">No items saved for later!</div>
      @endif
    </div>
    <!-- SAVED PRODUCTS ENDING -->
  </div>
</div>
@include('recommended')
@endsection
