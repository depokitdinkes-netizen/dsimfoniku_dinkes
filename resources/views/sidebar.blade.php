<aside id="sidebar" class="p-6 sm:p-8 bg-white h-screen fixed md:sticky top-0 text-white -translate-x-full md:translate-x-0 z-10 flex flex-col justify-between min-w-[18rem] rounded-r-3xl">
    <div class="flex flex-col items-start gap-8">
        <a href="{{ route('dashboard') }}" class="sm:mx-auto">
            <img src="{{ asset('logo/smart-healthy-city.png') }}" alt="depok smart healthy city" class="h-8 sm:h-12 object-cover" />
        </a>

        <div class="flex flex-col items-start w-full gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-block justify-start border-none {{ $page_name == 'dashboard' ? 'bg-blue-500/10 text-blue-500 hover:bg-blue-500/10' : 'text-black btn-ghost' }}">
                <i class="ri-dashboard-line"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('inspection') }}" class="btn btn-block justify-start border-none {{ $page_name == 'inspection' ? 'bg-blue-500/10 text-blue-500 hover:bg-blue-500/10' : 'text-black btn-ghost' }}">
                <i class="ri-file-edit-line"></i>
                <span>Lakukan Inspeksi</span>
            </a>
            @auth
            @if (Auth::user()->role != "USER")
            <a href="{{ route('history') }}" class="btn btn-block justify-start border-none {{ $page_name == 'history' ? 'bg-blue-500/10 text-blue-500 hover:bg-blue-500/10' : 'text-black btn-ghost' }}">
                <i class="ri-file-list-2-line"></i>
                <span>Histori Hasil Inspeksi</span>
            </a>
            @endif
            @endauth

            @auth
            @if (Auth::user()->role != "USER")
            <hr class="w-full" />

            <a href="{{ route('manajemen-user.index') }}" class="btn btn-block justify-start border-none {{ $page_name == 'user-management' ? 'bg-blue-500/10 text-blue-500 hover:bg-blue-500/10' : 'text-black btn-ghost' }}">
                <i class="ri-user-line"></i>
                <span>Manajemen User</span>
            </a>
            @endif
            @endauth
        </div>
    </div>

    @auth
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-block btn-ghost text-error hover:bg-gray-100 justify-start">
            <i class="ri-logout-box-r-line"></i><span>Keluar</span>
        </button>
    </form>
    @else
    <a href="{{ route('login') }}" class="btn btn-block justify-start border-none text-black btn-ghost">
        <i class="ri-login-box-line"></i><span>Masuk</span>
    </a>
    @endauth

    <button id="close-sidebar" class="absolute md:hidden top-5 right-5 btn btn-sm btn-square">
        <i class="ri-close-line"></i>
    </button>
</aside>
<button id="open-sidebar" class="fixed md:hidden top-5 right-5 btn btn-sm btn-square btn-outline z-20 bg-slate-50">
    <i class="ri-menu-line"></i>
</button>

<script src="{{ asset('js/toggleSidebar.js') }}"></script>
