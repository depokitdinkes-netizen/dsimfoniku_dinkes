@if(session('success'))
<div class="alert border-b-4 border-success fixed capitalize font-medium top-0 right-0 m-5 w-fit animate-fade-in z-20">
    {{ session('success') }}
    <button type="button" class="close-alert btn btn-ghost btn-square btn-sm">
        <i class="ri-check-line"></i>
    </button>
</div>
@endif

@if(session('error'))
<div class="alert border-b-4 border-error fixed capitalize font-medium top-0 right-0 m-5 w-fit animate-fade-in z-20">
    {{ session('error') }}
    <button type="button" class="close-alert btn btn-ghost btn-square btn-sm">
        <i class="ri-close-line"></i>
    </button>
</div>
@endif


<script src="{{ asset('js/removeToast.js') }}"></script>
</body>

</html>