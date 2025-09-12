@extends('layouts.app')

@section('content')
<div class="p-3 sm:p-6 border-b flex flex-wrap items-center justify-between gap-5">
    <h1 class="font-extrabold sm:text-xl">Pilih Form IKL</h1>
    <img src="{{ asset('logo/depok-city.png') }}" alt="depok city" class="h-6 sm:h-9 object-cover" />
</div>

<div class="p-3 sm:p-6">
    <h2 class="font-bold text-lg mb-4">Tempat Pengolahan Pangan</h2>
    <div class="grid grid-flow-row sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">


        <!-- RESTORAN -->
        <div class="dropdown dropdown-hover">
            <div tabindex="0" role="button" class="bg-white p-6 text-center rounded-lg">
                <i class="ri-restaurant-2-line text-7xl text-rose-500"></i>
                <h2 class="text-lg font-bold mt-1">Restoran</h2>
            </div>
            <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-lg w-52 p-2 shadow top-1/2 left-1/2 -translate-x-1/2">
                <li><a href="{{ route('restoran.create') }}" class="group">Restoran Umum <i class="ri-corner-down-right-line hidden group-hover:inline"></i></a></li>
                <li><a href="{{ route('restoran.create') }}?tipe=Hotel" class="font-semibold group">Restoran Hotel <i class="ri-corner-down-right-line hidden group-hover:inline"></i></a></li>
            </ul>
        </div>

        <!-- JASA BOGA KATERING -->
        <div class="dropdown dropdown-hover">
            <div tabindex="0" role="button" class="bg-white p-6 text-center rounded-lg">
                <i class="ri-restaurant-line text-7xl text-red-400"></i>
                <h2 class="text-lg font-bold mt-1">Jasa Boga</h2>
            </div>
            <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-lg w-52 p-2 shadow top-1/2 left-1/2 -translate-x-1/2 z-20">
                <li><a href="{{ route('jasa-boga-katering.create') }}" class="group">Golongan A <i class="ri-corner-down-right-line hidden group-hover:inline"></i></a></li>
                <li><a href="{{ route('jasa-boga-katering.create') }}?golongan=b" class="font-semibold group">Golongan B <i class="ri-corner-down-right-line hidden group-hover:inline"></i></a></li>
                <li><a href="{{ route('jasa-boga-katering.create') }}?golongan=c" class="group">Golongan C <i class="ri-corner-down-right-line hidden group-hover:inline"></i></a></li>
            </ul>
        </div>

        <!-- RUMAH MAKAN -->
        <div class="dropdown dropdown-hover">
            <div tabindex="0" role="button" class="bg-white p-6 text-center rounded-lg">
                <i class="ri-home-8-line text-7xl text-red-700"></i>
                <h2 class="text-lg font-bold mt-1">Rumah Makan</h2>
            </div>
            <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-lg w-72 p-2 shadow top-1/2 left-1/2 -translate-x-1/2 z-20">
                <li><a href="{{ route('rumah-makan.create') }}" class="group">Tipe A1 <i class="ri-corner-down-right-line hidden group-hover:inline"></i></a></li>
                <li><a href="{{ route('rumah-makan.create') }}?tipe=A2" class="font-semibold group">Tipe A2 <i class="ri-corner-down-right-line hidden group-hover:inline"></i></a></li>
            </ul>
        </div>

        <!-- KANTIN -->
        <a href="{{ route('kantin.create') }}" class="bg-white p-6 group text-center rounded-lg hover:-translate-y-1 hover:shadow-md transition-all">
            <i class="ri-store-3-line text-7xl text-orange-400"></i>
            <h2 class="text-lg font-bold mt-1">Sentra Kantin <i class="ri-corner-down-right-line hidden group-hover:inline"></i></h2>
        </a>

        <!-- DEPOT AIR MINUM -->
        <a href="{{ route('depot-air-minum.create') }}" class="bg-white p-6 group text-center rounded-lg hover:-translate-y-1 hover:shadow-md transition-all">
            <i class="ri-drinks-fill text-7xl text-blue-600"></i>
            <h2 class="text-lg font-bold mt-1">Depot Air Minum <i class="ri-corner-down-right-line hidden group-hover:inline"></i></h2>
        </a>

        <!-- SARANA AIR MINUM -->
        <div class="dropdown dropdown-hover">
            <div tabindex="0" role="button" class="bg-white p-6 text-center rounded-lg">
                <i class="ri-drop-fill text-7xl text-blue-400"></i>
                <h2 class="text-lg font-bold mt-1">Sarana Air Minum (SAM)</h2>
            </div>
            <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-lg w-72 p-2 shadow top-1/2 left-1/2 -translate-x-1/2 z-20">
                <li><a href="{{ route('penyimpanan-air-hujan.create') }}" class="font-semibold group">  Penyimpanan Air Hujan <i class="ri-corner-down-right-line hidden group-hover:inline"></i></a></li>
                <li><a href="{{ route('perlindungan-mata-air.create') }}" class="group">                Perlindungan Mata Air <i class="ri-corner-down-right-line hidden group-hover:inline"></i></a></li>
                <li><a href="{{ route('perpipaan-non-pdam.create') }}" class="font-semibold group">     Perpipaan Non PDAM <i class="ri-corner-down-right-line hidden group-hover:inline"></i></a></li>
                <li><a href="{{ route('perpipaan.create') }}" class="group">                            Perpipaan PDAM <i class="ri-corner-down-right-line hidden group-hover:inline"></i></a></li>
                <li><a href="{{ route('sumur-bor-pompa.create') }}" class="font-semibold group">        Sumur Bor dengan Pompa Tangan <i class="ri-corner-down-right-line hidden group-hover:inline"></i></a></li>
                <li><a href="{{ route('sumur-gali.create') }}" class="group">                           Sumur Gali dengan Kerekan <i class="ri-corner-down-right-line hidden group-hover:inline"></i></a></li>
            </ul>
        </div>

        <!-- GERAI PANGAN JAJANAN -->
        <a href="{{ route('gerai-pangan-jajanan.create') }}" class="bg-white group p-6 text-center rounded-lg hover:-translate-y-1 hover:shadow-md transition-all">
            <i class="ri-store-line text-7xl text-yellow-900"></i>
            <h2 class="text-lg font-bold mt-1">Gerai Pangan Jajanan <i class="ri-corner-down-right-line hidden group-hover:inline"></i></h2>
        </a>

        <!-- GERAI PANGAN JAJANAN KELILING -->
        <div class="dropdown dropdown-hover">
            <div tabindex="0" role="button" class="bg-white p-6 text-center rounded-lg">
                <i class="ri-store-2-line text-7xl text-yellow-800"></i>
                <h2 class="text-lg font-bold mt-1">Gerai Pangan Jajanan Keliling</h2>
            </div>
            <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-lg w-72 p-2 shadow top-1/2 left-1/2 -translate-x-1/2">
                <li><a href="{{ route('gerai-jajanan-keliling.create') }}" class="group">Golongan A1 <i class="ri-corner-down-right-line hidden group-hover:inline"></i></a></li>
                <li><a href="{{ route('gerai-jajanan-keliling.create') . '?golongan=a2' }}" class="font-semibold group">Golongan A2 <i class="ri-corner-down-right-line hidden group-hover:inline"></i></a></li>
            </ul>
        </div>
        @if(request("hide", "true") == "false")
        <!-- PASAR -->
        <div class="dropdown dropdown-hover">
            <div tabindex="0" role="button" class="bg-white p-6 text-center rounded-lg">
                <i class="ri-store-3-line text-7xl text-amber-500"></i>
                <h2 class="text-lg font-bold mt-1">Pasar</h2>
            </div>
            <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-lg w-52 p-2 shadow top-1/2 left-1/2 -translate-x-1/2">
                <li><a href="{{ route('pasar.create') }}" class="group">Pasar Eksternal <i class="ri-corner-down-right-line hidden group-hover:inline"></i></a></li>
                <li><a href="{{ route('pasar-internal.create') }}" class="font-semibold group">Pasar Internal <i class="ri-corner-down-right-line hidden group-hover:inline"></i></a></li>
            </ul>
        </div>
        @endif
        <!-- <a href="" class="bg-white p-6 text-center rounded-lg hover:-translate-y-1 hover:shadow-md transition-all opacity-30">
            <i class="ri-moon-clear-line text-7xl text-lime-600"></i>
            <h2 class="text-lg font-bold mt-1">Pondok Pesantren</h2>
        </a>
        <a href="" class="bg-white p-6 text-center rounded-lg hover:-translate-y-1 hover:shadow-md transition-all opacity-30">
            <i class="ri-contrast-2-fill text-7xl text-teal-500"></i>
            <h2 class="text-lg font-bold mt-1">Masjid</h2>
        </a> -->

    </div>

    <hr class="mt-6" />

    <h2 class="font-bold text-lg mt-6 mb-4">Tempat Fasilitas Umum</h2>

    <div class="grid grid-flow-row sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        <a href="{{ route('sekolah.create') }}" class="group bg-white p-6 text-center rounded-lg hover:-translate-y-1 hover:shadow-md transition-all">
            <i class="ri-graduation-cap-line text-7xl text-black/70"></i>
            <h2 class="text-lg font-bold mt-1">Sekolah <i class="ri-corner-down-right-line hidden group-hover:inline"></i></h2>
        </a>
        <a href="{{ route('rumah-sakit.create') }}" class="group bg-white p-6 text-center rounded-lg hover:-translate-y-1 hover:shadow-md transition-all">
            <i class="ri-hospital-line text-7xl text-slate-400"></i>
            <h2 class="text-lg font-bold mt-1">Rumah Sakit <i class="ri-corner-down-right-line hidden group-hover:inline"></i></h2>
        </a>
        @if(request("hide", "true") == "false")
        <a href="{{ route('tempat-ibadah.create') }}" class="bg-white p-6 text-center rounded-lg hover:-translate-y-1 hover:shadow-md transition-all">
            <i class="ri-moon-clear-line text-7xl text-lime-600"></i>
            <h2 class="text-lg font-bold mt-1">Tempat Ibadah</h2>
        </a>
        @endif
        <a href="{{ route('puskesmas.create') }}" class="group bg-white p-6 text-center rounded-lg hover:-translate-y-1 hover:shadow-md transition-all">
            <i class="ri-stethoscope-line text-7xl text-gray-500"></i>
            <h2 class="text-lg font-bold mt-1">Puskesmas <i class="ri-corner-down-right-line hidden group-hover:inline"></i></h2>
        </a>

        <a href="{{ route('tempat-rekreasi.create') }}" class="group bg-white p-6 text-center rounded-lg hover:-translate-y-1 hover:shadow-md transition-all">
            <i class="ri-sparkling-line text-7xl text-violet-500"></i>
            <h2 class="text-lg font-bold mt-1">Tempat Rekreasi <i class="ri-corner-down-right-line hidden group-hover:inline"></i></h2>
        </a>
        <a href="{{ route('renang-pemandian.create') }}" class="group bg-white p-6 text-center rounded-lg hover:-translate-y-1 hover:shadow-md transition-all">
            <i class="ri-community-line text-7xl text-sky-500"></i>
            <h2 class="text-lg font-bold mt-1">Kolam Renang <i class="ri-corner-down-right-line hidden group-hover:inline"></i></h2>
        </a>
        <a href="{{ route('akomodasi.create') }}" class="group bg-white p-6 text-center rounded-lg hover:-translate-y-1 hover:shadow-md transition-all">
            <i class="ri-home-office-line text-7xl text-stone-600"></i>
            <h2 class="text-lg font-bold mt-1">Akomodasi <i class="ri-corner-down-right-line hidden group-hover:inline"></i></h2>
        </a>

        <a href="{{ route('akomodasi-lain.create') }}" class="group bg-white p-6 text-center rounded-lg hover:-translate-y-1 hover:shadow-md transition-all">
            <i class="ri-home-office-fill text-7xl text-stone-700"></i>
            <h2 class="text-lg font-bold mt-1">Akomodasi Lainnya <i class="ri-corner-down-right-line hidden group-hover:inline"></i></h2>
        </a>

        <a href="{{ route('tempat-olahraga.create') }}" class="group bg-white p-6 text-center rounded-lg hover:-translate-y-1 hover:shadow-md transition-all">
            <i class="ri-building-4-line text-7xl text-orange-500"></i>
            <h2 class="text-lg font-bold mt-1">Gelanggang Olahraga <i class="ri-corner-down-right-line hidden group-hover:inline"></i></h2>
        </a>

        <!-- PASAR -->
        <div class="dropdown dropdown-hover">
            <div tabindex="0" role="button" class="bg-white p-6 text-center rounded-lg">
                <i class="ri-shopping-basket-line text-7xl text-green-600"></i>
                <h2 class="text-lg font-bold mt-1">Pasar</h2>
            </div>
            <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-lg w-52 p-2 shadow top-1/2 left-1/2 -translate-x-1/2">
                <li><a href="{{ route('pasar.create') }}" class="group">Pasar Eksternal <i class="ri-corner-down-right-line hidden group-hover:inline"></i></a></li>
                <li><a href="{{ route('pasar-internal.create') }}" class="font-semibold group">Pasar Internal <i class="ri-corner-down-right-line hidden group-hover:inline"></i></a></li>
            </ul>
        </div>

        <a href="{{ route('stasiun.create') }}" class="group bg-white p-6 text-center rounded-lg hover:-translate-y-1 hover:shadow-md transition-all">
            <i class="ri-train-line text-7xl text-indigo-600"></i>
            <h2 class="text-lg font-bold mt-1">Stasiun <i class="ri-corner-down-right-line hidden group-hover:inline"></i></h2>
        </a>
    </div>
</div>
@endsection
