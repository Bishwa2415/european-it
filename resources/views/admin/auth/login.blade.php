<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | European IT</title>
    <meta name="robots" content="noindex, nofollow">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/fsv.webp') }}">
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-screen items-center justify-center bg-[#202A38] px-5 antialiased">

<div class="w-full max-w-sm rounded-2xl bg-white p-8 shadow-xl">
    <img src="{{ asset('images/logo.webp') }}" alt="European IT" class="mx-auto w-48">
    <h1 class="mt-6 text-center font-heading text-xl font-bold text-heading">Dashboard Login</h1>

    <form method="POST" action="{{ route('admin.login.store') }}" class="mt-6 space-y-4">
        @csrf   {{-- hidden security token: Laravel rejects forms without it --}}

        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-heading">Email</label>
            {{-- old('email') = keep what was typed if the login fails --}}
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                   class="w-full rounded-lg border border-gray-300 px-3 py-2.5 outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="mb-1 block text-sm font-medium text-heading">Password</label>
            <input id="password" name="password" type="password" required
                   class="w-full rounded-lg border border-gray-300 px-3 py-2.5 outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
        </div>

        <label class="flex items-center gap-2 text-sm text-gray-600">
            <input type="checkbox" name="remember" class="rounded"> Remember me
        </label>

        <button class="w-full rounded-full bg-primary-btn py-3 text-sm font-bold uppercase text-white hover:bg-primary-hover transition">
            Login
        </button>
    </form>
</div>

</body>
</html>
