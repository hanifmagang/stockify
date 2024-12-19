
@extends('example.layouts.default.dashboard')
@section('content')

@section('title', 'Dashboard')

@vite(['resources/css/app.css','resources/js/app.js'])
<?php
use Carbon\Carbon; // Mengimpor Carbon
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



@if (auth()->user()->role === 'Admin')
<div class="px-4 pt-9">
    <div class="grid w-full grid-cols-1 gap-4 xl:grid-cols-2 2xl:grid-cols-3">
      <div class="items-center justify-between p-6 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex dark:border-gray-700 sm:p-8 dark:bg-gray-800 h-40"> <!-- Menambahkan h-40 -->
          <div class="w-full">
              <h3 class="text-xl font-bold leading-none text-gray-900 sm:text-xl dark:text-white">Jumlah Product</h3>
              <span class="text-xl font-bold leading-none text-gray-900 sm:text-3xl dark:text-white">{{ $totalProduct }}</span>
          </div>
          <svg class="flex-shrink-0 w-16 h-16 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
              <path d="M19 0H1a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1ZM2 6v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6H2Zm11 3a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1V8a1 1 0 0 1 2 0h2a1 1 0 0 1 2 0v1Z"/>
          </svg>
      </div>
      <div class="items-center justify-between p-6 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex dark:border-gray-700 sm:p-8 dark:bg-gray-800 h-40"> <!-- Menambahkan h-40 -->
          <div class="w-full">
              <h3 class="text-xl font-bold leading-none text-gray-900 sm:text-xl dark:text-white">Transaksi Masuk</h3>
              <span class="text-xl font-bold leading-none text-gray-900 sm:text-3xl dark:text-white">{{ $totalTransIn }}</span>
          </div>
          <svg class="flex-shrink-0 w-16 h-16 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
            <path d="M10 16l7-7h-4V0H7v9H3z"/> 
        </svg>
      </div>
      <div class="items-center justify-between p-6 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex dark:border-gray-700 sm:p-8 dark:bg-gray-800 h-40"> <!-- Menambahkan h-40 -->
          <div class="w-full">
              <h3 class="text-xl font-bold leading-none text-gray-900 sm:text-xl dark:text-white">Transaksi Keluar</h3>
              <span class="text-xl font-bold leading-none text-gray-900 sm:text-3xl dark:text-white">{{ $totalTransOut }}</span>
          </div>
          <svg class="flex-shrink-0 w-16 h-16 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 16">
            <path d="M10 0l-7 7h4v9h6v-9h4z"/> 
          </svg>
      </div>
  </div>
    <div class="mt-6 grid gap-4 xl:grid-cols-2 cols-9">
      <!-- Main widget -->
      <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
        <div class="flex items-center justify-between mb-4">
          <div class="flex-shrink-0">
            <span class="text-xl font-bold leading-none text-gray-900 sm:text-2xl dark:text-white">Grafik</span>
            <h3 class="text-base font-light text-gray-500 dark:text-gray-400">Stock Product</h3>
          </div>
          <div class="flex items-center justify-end flex-1 text-base font-medium text-green-500 dark:text-green-400">
            <select id="year-filter" class="ml-2 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
              @for ($year = date('Y'); $year >= 2020; $year--) <!-- Menambahkan dropdown tahun -->
                  <option value="{{ $year }}">{{ $year }}</option>
              @endfor
            </select>
          </div>
        </div>
        <canvas id="stockChart" width="200px" height="75px"></canvas>
        <!-- Card Footer -->
        {{-- <div class="flex items-center justify-between pt-3 mt-4 border-t border-gray-200 sm:pt-6 dark:border-gray-700">
          <div>
            <button class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 rounded-lg hover:text-gray-900 dark:text-gray-400 dark:hover:text-white" type="button" data-dropdown-toggle="weekly-sales-dropdown">Last 7 days <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button>
            <!-- Dropdown menu -->
            <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="weekly-sales-dropdown">
                <div class="px-4 py-3" role="none">
                  <p class="text-sm font-medium text-gray-900 truncate dark:text-white" role="none">
                    Sep 16, 2021 - Sep 22, 2021
                  </p>
                </div>
                <ul class="py-1" role="none">
                  <li>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Yesterday</a>
                  </li>
                  <li>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Today</a>
                  </li>
                  <li>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Last 7 days</a>
                  </li>
                  <li>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Last 30 days</a>
                  </li>
                  <li>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Last 90 days</a>
                  </li>
                </ul>
                <div class="py-1" role="none">
                  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Custom...</a>
                </div>
            </div>
          </div>
          <div class="flex-shrink-0">
            <a href="#" class="inline-flex items-center p-2 text-xs font-medium uppercase rounded-lg text-primary-700 sm:text-sm hover:bg-gray-100 dark:text-primary-500 dark:hover:bg-gray-700">
              Sales Report
              <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
          </div>
        </div> --}}
      </div>
      
    </div>

</div>
@endif


@if (auth()->user()->role === 'Manajer Gudang')
<div class="px-4 pt-6">
  <div class="grid gap-4 ">
    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
      <!-- Card header -->
      <div class="items-center justify-between lg:flex">
        <div class="mb-4 lg:mb-0">
          <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Stock</h3>
          <span class="text-base font-normal text-gray-500 dark:text-gray-400">Ini adalah daftar Produk yang stocknya menipis atau habis.</span>
        </div>
      </div>
      <!-- Table -->
      <div class="flex flex-col mt-6">
        <div class="overflow-x-auto rounded-lg">
          <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow sm:rounded-lg">
              <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                <thead class="bg-gray-50 dark:bg-gray-700">
                  <tr>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      Image
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      SKU
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      Category
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      Product Name
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      Stock
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      Status Stock
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800">
                  @foreach($stockOpname as $opname)
                  @if($opname['stock_akhir'] < $opname->product->stockMinimum) <!-- Stock Minimum -->
                  <tr>
                    <td class="p-4 text-sm font-semibold text-gray-900 whitespace-nowrap dark:text-white">
                      @if($opname->product->image)
                          <img src="{{ asset('storage/' . $opname->product->image) }}" alt="{{ $opname->product->name }}" class="w-16 h-16 object-cover rounded-lg">
                      @else
                          <span>No Image</span>
                      @endif
                    </td>
                    <td class="p-4 text-sm font-semibold text-gray-900 whitespace-nowrap dark:text-white">
                      {{ $opname->product->sku }}
                    </td>
                    <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                      {{ $opname->category->name }}
                    </td>
                    <td class="p-4 text-sm font-normal text-gray-900 whitespace-nowrap dark:text-white">
                      {{ $opname->product->name }}
                    </td>
                    <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                      {{ $opname['stock_akhir']}}
                    </td>
                    
                    <td class="p-4 whitespace-nowrap">
                      @if($opname['stock_akhir'] === 0)
                        <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-red-100 dark:bg-gray-700 dark:border-red-500 dark:text-red-400">Stock Habis</span>
                      @elseif($opname['stock_akhir'] < $opname->product->stockMinimum)
                        <span class="bg-orange-100 text-orange-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-orange-100 dark:bg-gray-700 dark:border-orange-300 dark:text-orange-300">Stock Menipis</span>
                      @else
                        <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-green-100 dark:bg-gray-700 dark:border-green-500 dark:text-green-400">Stock Aman</span>
                      @endif
                    </td>
                  </tr>
                  @endif
                  @endforeach
                  
                
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      {{-- <!-- Card Footer -->
      @if(auth()->user()->role === 'Admin')
      <div class="flex items-center justify-between pt-3 sm:pt-6">
        <div>
          <button class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 rounded-lg hover:text-gray-900 dark:text-gray-400 dark:hover:text-white" type="button" data-dropdown-toggle="transactions-dropdown">Last 7 days <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button>
          <!-- Dropdown menu -->
          <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="transactions-dropdown">
              <div class="px-4 py-3" role="none">
                <p class="text-sm font-medium text-gray-900 truncate dark:text-white" role="none">
                  Sep 16, 2021 - Sep 22, 2021
                </p>
              </div>
              <ul class="py-1" role="none">
                <li>
                  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Today</a>
                </li>
                <li>
                  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Yesterday</a>
                </li>
                <li>
                  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Last 7 days</a>
                </li>
                <li>
                  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Last 30 days</a>
                </li>
              </ul>
              <div class="py-1" role="none">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Custom...</a>
              </div>
          </div>
        </div>
        <div class="flex-shrink-0">
          <a href="#" class="inline-flex items-center p-2 text-xs font-medium uppercase rounded-lg text-primary-700 sm:text-sm hover:bg-gray-100 dark:text-primary-500 dark:hover:bg-gray-700">
          Stock Report
            <svg class="w-4 h-4 ml-1 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
          </a>
        </div>
      </div>
      @endif --}}
    </div>
  </div>
</div>
@endif




<div class="px-4 pt-6">
  <div class="grid gap-4 ">
    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
      <!-- Card header -->
      <div class="items-center justify-between lg:flex">
        <div class="mb-4 lg:mb-0">
          <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Transaksi</h3>
          @if (auth()->user()->role === 'Admin')
          <span class="text-base font-normal text-gray-500 dark:text-gray-400">Ini adalah daftar transaksi masuk dan keluar</span>  
          @endif
          @if (auth()->user()->role === 'Staff Gudang')
          <span class="text-base font-normal text-gray-500 dark:text-gray-400">Ini adalah daftar transaksi yang perlu diperiksa.</span>  
          @endif
          @if (auth()->user()->role === 'Manajer Gudang')
          <span class="text-base font-normal text-gray-500 dark:text-gray-400">Ini adalah daftar transaksi hari ini</span>
          @endif
        </div>
        <div class="items-center sm:flex">

          @if(auth()->user()->role === 'Admin')
          <form method="GET" action="{{ route('dashboard.tampil') }}">
            <div class="flex items-center space-x-4">
              <div class="col-span-6 sm:col-span-3 flex items-center mr-3">
                <label for="start-date" class="block text-sm font-medium text-gray-900 dark:text-white mr-1">Start Date</label>
                <input type="date" name="start-date" id="start-date" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
              </div>

              <div class="col-span-6 sm:col-span-3 flex items-center mr-3">
                  <label for="end-date" class="block text-sm font-medium text-gray-900 dark:text-white mr-1">End Date</label>
                  <input type="date" name="end-date" id="end-date" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
              </div>
              <button type="submit" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                </svg>
                Filter
              </button>
            </div>
          </form>
          @endif
        </div>
      </div>
      <!-- Table -->
      <div class="flex flex-col mt-6">
        <div class="overflow-x-auto rounded-lg">
          <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow sm:rounded-lg">
              <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                <thead class="bg-gray-50 dark:bg-gray-700">
                  <tr>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      Product Name
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      Category
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      Quantity
                    </th>
                    @if (auth()->user()->role === 'Admin')
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      Current Stock
                    </th>
                    @endif
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      Time
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      Date
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      Status
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800">
                  @foreach($transactions as $transaction)
                  @if(auth()->user()->role === 'Admin')
                  @if(($transaction['type'] === 'Masuk' && $transaction['status'] === 'Diterima') || 
                    ($transaction['type'] === 'Keluar' && $transaction['status'] === 'Dikeluarkan'))
                  <tr>
                      <td class="p-4 text-sm font-semibold text-gray-900 whitespace-nowrap dark:text-white">
                          {{ $transaction->product->name }}
                      </td>
                      <td class="p-4 text-sm font-semibold text-gray-900 whitespace-nowrap dark:text-white">
                          {{ $transaction->product->category->name }}
                      </td>
                      <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                        @if($transaction['type'] === 'Masuk')
                          +{{ $transaction['quantity'] }}
                        @else
                          -{{ $transaction['quantity'] }}
                        @endif
                      </td>
                      <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                          {{ $transaction['stockSementara'] }}
                      </td>
                      <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                          {{ $transaction['updated_at']->setTimezone('Asia/Jakarta')->format('H:i:s') }}
                      </td>
                      <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                          {{ $transaction['updated_at']->setTimezone('Asia/Jakarta')->format('Y-m-d') }}
                      </td>
                      <td class="p-4 whitespace-nowrap">
                          @if($transaction['status'] === 'Dikeluarkan')
                              <span class="bg-orange-100 text-orange-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-orange-100 dark:bg-gray-700 dark:border-orange-300 dark:text-orange-300">Dikeluarkan</span>
                          @elseif($transaction['status'] === 'Diterima') 
                              <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-green-100 dark:bg-gray-700 dark:border-green-500 dark:text-green-400">Diterima</span>
                          @elseif($transaction['status'] === 'Pending')
                              <span class="bg-purple-100 text-purple-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-purple-100 dark:bg-gray-700 dark:border-purple-500 dark:text-purple-400">Pending</span>
                          @elseif($transaction['status'] === 'Ditolak')
                              <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-red-100 dark:bg-gray-700 dark:border-red-500 dark:text-red-400">Ditolak</span>
                          @else
                              {{ $transaction['status'] }}
                          @endif
                      </td>
                  </tr>
                  @endif
                  @endif
                  @if(auth()->user()->role === 'Staff Gudang')
                  @if($transaction['status'] === 'Pending')
                  <tr>
                    <td class="p-4 text-sm font-semibold text-gray-900 whitespace-nowrap dark:text-white">
                      {{ $transaction->product->name }}
                    </td>
                    <td class="p-4 text-sm font-semibold text-gray-900 whitespace-nowrap dark:text-white">
                      {{ $transaction->product->category->name }}
                    </td>
                    <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                      @if($transaction['type'] === 'Masuk')
                        +{{ $transaction['quantity'] }}
                      @else
                        -{{ $transaction['quantity'] }}
                      @endif
                    </td>
                    <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                      {{ $transaction['updated_at']->setTimezone('Asia/Jakarta')->format('H:i:s') }}
                    </td>
                    <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                      {{ $transaction['updated_at']->setTimezone('Asia/Jakarta')->format('Y-m-d') }}
                    </td>
                    <td class="p-4 whitespace-nowrap"> 
                        <span class="bg-purple-100 text-purple-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-purple-100 dark:bg-gray-700 dark:border-purple-500 dark:text-purple-400">{{ $transaction['status'] }}</span>
                    </td>
                  </tr>
                  @endif
                  @endif
                  @if(auth()->user()->role === 'Manajer Gudang')
                  @if(($transaction['type'] === 'Masuk' && $transaction['status'] === 'Diterima') || 
                    ($transaction['type'] === 'Keluar' && $transaction['status'] === 'Dikeluarkan'))
                  @if(\Carbon\Carbon::parse($transaction['date'])->isToday())
                  <tr>
                    <td class="p-4 text-sm font-semibold text-gray-900 whitespace-nowrap dark:text-white">
                      {{ $transaction->product->name }}
                    </td>
                    <td class="p-4 text-sm font-normal text-gray-900 whitespace-nowrap dark:text-white">
                      {{ $transaction->product->category->name }}
                    </td>
                    <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                      @if($transaction['type'] === 'Masuk')
                        +{{ $transaction['quantity'] }}
                      @else
                        -{{ $transaction['quantity'] }}
                      @endif
                    </td>
                    <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                      {{ $transaction['updated_at']->setTimezone('Asia/Jakarta')->format('H:i:s') }}
                    </td>
                    <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                      {{ $transaction['updated_at']->setTimezone('Asia/Jakarta')->format('Y-m-d') }}
                    </td>
                    
                    <td class="p-4 whitespace-nowrap">
                      @if($transaction['status'] === 'Dikeluarkan')
                        <span class="bg-orange-100 text-orange-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-orange-100 dark:bg-gray-700 dark:border-orange-300 dark:text-orange-300">Dikeluarkan</span>
                      @elseif($transaction['status'] === 'Diterima') 
                        <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-green-100 dark:bg-gray-700 dark:border-green-500 dark:text-green-400">Diterima</span>
                      @elseif($transaction['status'] === 'Pending')
                        <span class="bg-purple-100 text-purple-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-purple-100 dark:bg-gray-700 dark:border-purple-500 dark:text-purple-400">Pending</span>
                      @elseif($transaction['status'] === 'Ditolak')
                        <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-red-100 dark:bg-gray-700 dark:border-red-500 dark:text-red-400">Ditolak</span>
                      @else
                        {{ $transaction['status'] }}
                      @endif
                    </td>
                  </tr>
                  @endif
                  @endif
                  @endif
                  @endforeach
                  
                
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- Card Footer -->
      @if(auth()->user()->role === 'Admin')
      <div class="flex items-center justify-between pt-3 sm:pt-6">
        <div>
          {{-- <button class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 rounded-lg hover:text-gray-900 dark:text-gray-400 dark:hover:text-white" type="button" data-dropdown-toggle="transactions-dropdown">Last 7 days <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button>
          <!-- Dropdown menu -->
          <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="transactions-dropdown">
              <div class="px-4 py-3" role="none">
                <p class="text-sm font-medium text-gray-900 truncate dark:text-white" role="none">
                  Sep 16, 2021 - Sep 22, 2021
                </p>
              </div>
              <ul class="py-1" role="none">
                <li>
                  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Today</a>
                </li>
                <li>
                  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Yesterday</a>
                </li>
                <li>
                  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Last 7 days</a>
                </li>
                <li>
                  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Last 30 days</a>
                </li>
              </ul>
              <div class="py-1" role="none">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Custom...</a>
              </div>
          </div> --}}
        </div>
        <div class="flex-shrink-0">
          <a href="{{ route('laporan.stock.tampil') }}" class="inline-flex items-center p-2 text-xs font-medium uppercase rounded-lg text-primary-700 sm:text-sm hover:bg-gray-100 dark:text-primary-500 dark:hover:bg-gray-700">
          Transactions Report
            <svg class="w-4 h-4 ml-1 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
          </a>
        </div>
      </div>
      @endif
      <!-- Footer -->
      <div class="sticky mt-5 bottom-0 right-0 items-center w-full p-4 bg-white border-t border-gray-200 sm:flex sm:justify-between dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center mb-4 sm:mb-0">
          <a href="{{ $transactions->previousPageUrl() }}" class="inline-flex justify-center p-1 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
              <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
          </a>
          <a href="{{ $transactions->nextPageUrl() }}" class="inline-flex justify-center p-1 mr-2 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
              <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
          </a>
          <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Showing <span class="font-semibold text-gray-900 dark:text-white">{{ $transactions->firstItem() }}-{{ $transactions->lastItem() }}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{ $transactions->total() }}</span></span>
      </div>
      <div class="flex items-center space-x-3">
          <a href="{{ $transactions->Url(1) }}" class="inline-flex items-center justify-center flex-1 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 {{ $transactions->onFirstPage() ? 'cursor-not-allowed opacity-50' : '' }}">
              First
          </a>
          <a href="{{ $transactions->previousPageUrl() }}" class="inline-flex items-center justify-center flex-1 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
              «
          </a>
          @php
              $start = max($transactions->currentPage() - 1, 1);
              $end = min($transactions->currentPage() + 1, $transactions->lastPage());
          @endphp
          <div class="flex items-center space-x-2">
              @if ($start > 1)
                  <a href="{{ $transactions->url(1) }}" class="inline-flex items-center justify-center flex-1 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">1</a>
                  @if ($start > 2)
                      <div class="text-gray-500">...</div>
                  @endif
              @endif
              @for ($i = $start; $i <= $end; $i++)
                  <a href="{{ $transactions->url($i) }}" class="inline-flex items-center justify-center flex-1 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 {{ $transactions->currentPage() == $i ? 'bg-primary-800' : '' }}">{{ $i }}</a>
              @endfor
              @if ($end < $transactions->lastPage())
                  @if ($end < $transactions->lastPage() - 1)
                      <div class="text-gray-500">...</div>
                  @endif
                  <a href="{{ $transactions->url($transactions->lastPage()) }}" class="inline-flex items-center justify-center flex-1 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">{{ $transactions->lastPage() }}</a>
              @endif
          </div>
          <a href="{{ $transactions->nextPageUrl() }}" class="inline-flex items-center justify-center flex-1 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
              »
          </a>
          <a href="{{ $transactions->url($transactions->lastPage()) }}" class="inline-flex items-center justify-center flex-1 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 {{ $transactions->currentPage() == $transactions->lastPage() ? 'cursor-not-allowed opacity-50' : '' }}">
              Last
          </a>
      </div>
      </div>
    </div>
    
  </div>
  
</div>






{{-- @if (auth()->user()->role === 'Admin')
<div class="px-4 pt-6">
  <div class="grid gap-4 ">
    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
      <!-- Card header -->
      <div class="items-center justify-between lg:flex">
        <div class="mb-4 lg:mb-0">
          <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">User Activity</h3>
          @if (auth()->user()->role === 'Admin' || auth()->user()->role === 'Staff Gudang')
          <span class="text-base font-normal text-gray-500 dark:text-gray-400">Ini adalah daftar aktivitas pengguna dalam waktu terakhir</span>  
          @endif
          @if (auth()->user()->role === 'Manajer Gudang')
          <span class="text-base font-normal text-gray-500 dark:text-gray-400">Ini adalah daftar transaksi hari ini</span>
          @endif
        </div>
        
      </div>
      <!-- Table -->
      <div class="flex flex-col mt-6">
        <div class="overflow-x-auto rounded-lg">
          <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow sm:rounded-lg">
              <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                <thead class="bg-gray-50 dark:bg-gray-700">
                  <tr>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      Role
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      User Name
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      Activity
                    </th>
                    <th scope="col" class="p-4 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-white">
                      Date and Time
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800">
                  @php
                      $currentPage = request()->get('page', 1); 
                      $itemsPerPage = 10; // Jumlah item per halaman
                      $totalActivity = $activities->count(); // Total transaksi
                      $activities = $activities->sortByDesc('created_at');
                      $activityToShow = $activities->slice(($currentPage - 1) * $itemsPerPage, $itemsPerPage); 
                  @endphp
                  @foreach($activityToShow as $activity)
                  <tr>
                    <td class="p-4 text-sm font-semibold text-gray-900 whitespace-nowrap dark:text-white">
                      {{ $activity->user->role }}
                    </td>
                    <td class="p-4 text-sm font-normal text-gray-900 whitespace-nowrap dark:text-white">
                      {{ $activity->user->name }}
                    </td>
                    <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                      {{ $activity->activity }}
                    </td>
                    <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                      {{ $activity->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s') }}
                    </td>
                    
                  </tr>

                  @endforeach
                  
                
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- Card Footer -->
      <div class="flex items-center justify-between pt-3 sm:pt-6">

        <div class="flex-shrink-0">
          <a href="#" class="inline-flex items-center p-2 text-xs font-medium uppercase rounded-lg text-primary-700 sm:text-sm hover:bg-gray-100 dark:text-primary-500 dark:hover:bg-gray-700">
          Transactions Report
            <svg class="w-4 h-4 ml-1 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
          </a>
        </div>
      </div>

      <!-- Footer -->
      <div class="sticky mt-5 bottom-0 right-0 items-center w-full p-4 bg-white border-t border-gray-200 sm:flex sm:justify-between dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center mb-4 sm:mb-0">
            
            @if (auth()->user()->role === 'Admin' || auth()->user()->role === 'Staff Gudang')
            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Showing <span class="font-semibold text-gray-900 dark:text-white">{{ ($currentPage - 1) * $itemsPerPage + 1 }}-{{ min($currentPage * $itemsPerPage, $totalActivity) }}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{ $totalActivity }}</span></span>
            @endif
        </div>
        <div class="flex items-center space-x-3">
          <a href="{{ request()->fullUrlWithQuery(['page' => 1]) }}" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 {{ $currentPage === 1 ? 'disabled' : '' }}">
            First
          </a>
          @if ($currentPage > 1)
              <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                  «
              </a>
          @endif

          <!-- Pagination Numbers -->
          @for ($i = 1; $i <= ceil($totalActivity / $itemsPerPage); $i++)
              <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg {{ $currentPage === $i ? 'bg-blue-600' : 'bg-primary-700 hover:bg-primary-800' }} focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                  {{ $i }}
              </a>
          @endfor

          @if ($currentPage < ceil($totalActivity / $itemsPerPage))
              <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                  »
              </a>
          @endif

          <a href="{{ request()->fullUrlWithQuery(['page' => ceil($totalActivity / $itemsPerPage)]) }}" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 {{ $currentPage === ceil($totalActivity / $itemsPerPage) ? 'disabled' : '' }}">
              Last
          </a>
        </div>
      </div>
    </div>
    
  </div>
  
</div>
@endif --}}

<script>
  // Menghitung jumlah stok per bulan
  const transactions = @json($trans);
  const stockPerMonth = Array(12).fill(0); // Array untuk menyimpan jumlah stok per bulan

  transactions.forEach(transaction => {
      const month = new Date(transaction.updated_at).getMonth(); // Mendapatkan bulan dari tanggal transaksi
      if (transaction.type === 'Masuk' && transaction.status === 'Diterima') {
          stockPerMonth[month] += transaction.quantity; // Menambahkan jumlah untuk transaksi masuk
      } else if (transaction.type === 'Keluar' && transaction.status === 'Dikeluarkan') {
          stockPerMonth[month] -= transaction.quantity; // Mengurangi jumlah untuk transaksi keluar
      }
  });

  const ctx = document.getElementById('stockChart').getContext('2d');
  const stockChart = new Chart(ctx, {
      type: 'line', // Mengubah tipe grafik menjadi line
      data: {
          labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'], // Mengubah label menjadi nama bulan
          datasets: [{
              label: 'Jumlah Stok',
              data: stockPerMonth, // Menggunakan jumlah stok per bulan
              backgroundColor: '#fcba8d', // Kuning transparan
              borderColor: '#fcba8d',
              borderWidth: 2 // Mengubah ketebalan garis
          }]
      },
      options: {
          scales: {
              y: {
                  beginAtZero: true
              }
          }
      }
  });
</script>

@endsection