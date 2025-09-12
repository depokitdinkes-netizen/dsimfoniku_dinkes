@include('header')
<div class="flex justify-start bg-slate-50">
    @include('sidebar')
    <div class="w-full min-h-screen">
        @yield('content')
    </div>
</div>

@include('footer')