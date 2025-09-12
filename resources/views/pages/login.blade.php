@extends('layouts.single')

@section('content')
<form action="{{ route('auth') }}" method="POST" class="bg-white p-10 rounded-xl">
    @csrf
    @method('POST')

    <h1 class="text-3xl font-bold">Masuk</h1>

    <p class="mb-6 mt-3">Silakan masukkan informasi pengguna Anda.</p>

    <div class="input-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Masukan Email" class="sm:min-w-80" />
    </div>
    @if(isset($error_email))
    <p class="mt-2 text-error text-sm">{{ $error_email }}</p>
    @endif

    <div class="input-group mt-4">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Masukan Password" class="sm:min-w-80" />
    </div>
    @if(isset($error_password))
    <p class="mt-2 text-error text-sm">{{ $error_password }}</p>
    @endif

    {{-- <div class="form-control mt-2">
        <label class="label cursor-pointer justify-start gap-2">
            <input type="checkbox" checked="checked" class="checkbox checkbox-primary rounded-sm" />
            <span class="label-text">Ingat Username</span>
        </label>
    </div> --}}

    <button type="submit" class="btn btn-primary mt-5 btn-block">LOGIN</button>
</form>
@endsection
