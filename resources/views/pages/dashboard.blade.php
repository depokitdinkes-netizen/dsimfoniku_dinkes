@extends('layouts.app')

@section('content')

<div class="p-3 sm:p-6 border-b flex items-center justify-between gap-5">
    <h1 class="font-extrabold sm:text-xl">
        @auth
            Selamat Datang, {{ Auth::user()->fullname }}
        @else
            Selamat Datang
        @endauth
    </h1>
    <img src="{{ asset('logo/depok-city.png') }}" alt="depok city" class="h-6 sm:h-9 object-cover" />
</div>
<div class="grid grid-flow-row md:grid-cols-2 lg:grid-cols-3 gap-5 p-3 sm:p-6">
    <div class="md:col-span-2 bg-white p-3 sm:p-6 rounded-xl">
        <h2 class="font-bold text-lg mb-5">Menu Utama</h2>
        <div class="grid grid-flow-row md:grid-cols-2 gap-5">
            <a href="{{ route('inspection') }}" class="p-6 rounded-lg bg-blue-400 text-white flex items-center gap-2 relative hover:-translate-y-0.5 hover:shadow-sm transition-all">
                <i class="ri-survey-line text-6xl font-bold"></i>
                <div>
                    <h3 class="font-semibold text-xl">18 Form <i class="ri-corner-down-right-line"></i></h3>
                    <p class="text-sm font-medium">Inspeksi Lingkungan Kesehatan</p>
                </div>
            </a>
            <a href="{{ route('history') }}" class="p-6 rounded-lg bg-blue-50 text-blue-500  flex items-center gap-2 relative hover:-translate-y-0.5 hover:shadow-sm transition-all">
                <i class="ri-article-line text-6xl font-bold"></i>
                <div>
                    <h3 class="font-semibold text-xl">{{ $total_results }} Hasil <i class="ri-corner-down-right-line"></i></h3>
                    <p class="text-sm">Inspeksi Lingkungan Kesehatan</p>
                </div>
            </a>
        </div>
    </div>
    <div class="md:col-span-2 lg:col-span-1 bg-white p-3 sm:p-6 flex flex-col gap-1 justify-between rounded-xl">
        <h2 class="font-bold text-lg mb-5">Total Inspeksi <span class="text-xs font-base">/ Tahun</span></h2>

        <div class="flex items-end gap-5 p-6 rounded-lg bg-stone-50 pt-8">
            @foreach ($total_results_by_year as $year => $total)
            <p class="font-black flex items-end gap-1 flex-wrap leading-tight text-3xl sm:text-4xl md:text-5xl {{ !$loop->first ? 'text-gray-400' : '' }}">{{ $total }}<span class="text-sm md:text-base font-medium text-gray-400">{{ $year }}</span></p>
            @endforeach
        </div>
    </div>


    <div class="md:col-span-2 lg:col-span-3 bg-white p-3 sm:p-6 rounded-xl">

        <h2 class="font-bold text-lg mb-5">Hasil Inspeksi Terakhir</h2>
        <div class="grid grid-flow-row md:grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach ($inspections as $inspection)

            <a href="{{ url($inspection['sud']) }}" class="p-6 bg-neutral-100 rounded-lg relative hover:-translate-y-1 hover:shadow-md transition-all col-span-1 {{$loop->last ? 'lg:col-span-2 xl:col-span-1' : ''}}">
                <div class="flex items-center gap-2">
                    <i class="{{ $inspection['icon'] }} text-{{ $inspection['color'] }} text-lg"></i>
                    <p class="text-xs">{{ $inspection['title'] }}</p>
                </div>

                <h3 class="font-semibold mt-2">{{ $inspection['name'] }}</h3>
                <p class="text-sm line-clamp-2">{{ $inspection['address'] }}</p>
                <button class="btn btn-xs btn-neutral mt-2"><span>Lihat Detail</span> <i class="ri-arrow-right-line"></i></button>
            </a>
            @endforeach
        </div>
    </div>

</div>


@endsection