<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $album->name }} - Album Pinwave</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-sans text-gray-900 antialiased max-h-[400px] overflow-y-auto
[&::-webkit-scrollbar]:w-2
[&::-webkit-scrollbar-track]:rounded-full
[&::-webkit-scrollbar-track]:bg-gray-100
[&::-webkit-scrollbar-thumb]:rounded-full
[&::-webkit-scrollbar-thumb]:bg-gray-300">

    <!-- ========== HEADER ========== -->
    <header class="flex flex-wrap sm:justify-start sm:flex-nowrap z-50 w-full shadow-lg bg-white text-sm py-3 sm:py-0">
        <nav class="relative max-w-xxl w-full mx-auto px-4 sm:flex sm:items-center sm:justify-between"
            aria-label="Global">
            <div class="flex items-center justify-between">
                <a class="inline-flex items-center gap-x-2 text-xl font-bold" href="{{ url('/') }}">
                    <img class="w-12 h-auto" src="{{ asset('images/logo/pinwave.png') }}" alt="Logo">
                    Pinwave
                </a>
                <div class="sm:hidden flex">
                    @if (Auth::check())
                        <div class="hs-tooltip [--trigger:click]">
                            <div class="hs-tooltip-toggle">
                                <button type="button"
                                    class="mr-2 size-9 flex justify-center items-center text-sm font-semibold rounded-lg border border-gray-200 text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"
                                        stroke-linecap="round" stroke-linejoin="round" class="size-6 text-gray-500">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                                        <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
                                    </svg>
                                </button>
                            </div>
                            <div class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible hidden opacity-0 transition-opacity absolute invisible z-10 max-w-xs bg-white border border-gray-100 text-start rounded-lg shadow-md"
                                role="tooltip">
                                <span
                                    class="py-3 px-4 block text-lg font-bold text-gray-800 border-b border-gray-200">🔔
                                    Notification</span>
                                <div class="px-4 text-sm text-gray-600">
                                    @if (Auth::check())
                                        <ul class="max-w-xs flex flex-col divide-y divide-gray-200">
                                            @if (Auth::user()->unreadNotifications->isEmpty())
                                                <li
                                                    class="inline-flex items-center gap-x-2 py-3 text-sm font-medium text-gray-800">
                                                    Tidak ada notifikasi
                                                </li>
                                            @else
                                                @foreach (Auth::user()->unreadNotifications as $notification)
                                                    <li
                                                        class="inline-flex items-center gap-x-2 py-3 text-sm font-medium text-gray-800">
                                                        {{ App\Models\User::find($notification->data['liker_id'])->name }}
                                                        {{ $notification->data['message'] }}
                                                    </li>
                                                @endforeach
                                            @endif
                                        @else
                                            <li
                                                class="inline-flex items-center gap-x-2 py-3 text-sm font-medium text-gray-800">
                                                Anda harus login terlebih dahulu
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    <div>
                        <div class="hs-dropdown relative inline-flex">
                            @auth
                                <button id="hs-dropdown-custom-trigger" type="button" class="hs-dropdown-toggle">
                                    @if (Auth::user()->profile_photo_path)
                                        <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}"
                                            alt="{{ Auth::user()->username }}"
                                            class="w-9 h-9 rounded-lg border border-gray-200">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->username }}&background=DBEAFE&color=1E40AF&bold=true"
                                            alt="{{ Auth::user()->username }}" class="w-9 h-9 rounded-lg">
                                    @endif
                                </button>
                                <div style="z-index: 100"
                                    class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg p-2 mt-2 "
                                    aria-labelledby="hs-dropdown-custom-trigger">
                                    <div class="py-3 px-5 -m-2 bg-gray-100 rounded-t-lg">
                                        <p class="text-sm text-gray-500">Signed in as</p>
                                        <p class="text-sm font-medium text-gray-800">{{ Auth::user()->username }}</p>
                                    </div>
                                    <div class="mt-2 py-2 first:pt-0 last:pb-0">
                                        <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                            href="{{ url('/user/' . Auth::user()->username) }}">
                                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                            </svg>
                                            Profile
                                        </a>
                                        <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                            href="{{ url('/profile') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="flex-shrink-0 size-4 icon icon-tabler icons-tabler-outline icon-tabler-lock">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z" />
                                                <path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" />
                                                <path d="M8 11v-4a4 4 0 1 1 8 0v4" />
                                            </svg>
                                            Account Management
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                                href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                                <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-logout">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                                    <path d="M9 12h12l-3 -3" />
                                                    <path d="M18 15l3 -3" />
                                                </svg>
                                                Logout
                                            </a>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <a class="sm:border-s sm:border-gray-300 py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-blue-600 text-blue-600 hover:border-blue-500 hover:text-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:border-blue-500 dark:text-blue-500 dark:hover:text-blue-400 dark:hover:border-blue-400"
                                    href="{{ url('/login') }}">Get Started</a>
                            @endauth
                        </div>
                    </div>
                    <a href="#" class="text-gray-400 mx-4 my-auto">|</a>
                    <button type="button"
                        class="hs-collapse-toggle size-9 flex justify-center items-center text-sm font-semibold rounded-lg border border-gray-200 text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none"
                        data-hs-collapse="#navbar-collapse-with-animation"
                        aria-controls="navbar-collapse-with-animation" aria-label="Toggle navigation">
                        <svg class="hs-collapse-open:hidden size-4" width="16" height="16"
                            fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
                        </svg>
                        <svg class="hs-collapse-open:block flex-shrink-0 hidden size-4" width="16" height="16"
                            fill="currentColor" viewBox="0 0 16 16">
                            <path
                                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                        </svg>
                    </button>
                </div>
            </div>
            <div id="navbar-collapse-with-animation"
                class="hs-collapse hidden overflow-hidden transition-all duration-300 basis-full grow sm:block">
                <div
                    class="flex flex-col gap-y-4 gap-x-0 mt-5 sm:flex-row sm:items-center sm:justify-end sm:gap-y-0 sm:gap-x-7 sm:mt-0 sm:ps-7">
                    <a href="#" class="text-gray-400 hidden sm:block">|</a>
                    <a class="font-semibold text-blue-600 sm:py-6" href="{{ url('/') }}"
                        aria-current="page">Home</a>
                    <a class="font-semibold text-gray-500 hover:text-gray-400 sm:py-6"
                        href="{{ url('/pins/create') }}">Create Pin</a>
                    <div class="flex-1 items-center">
                        <hr class="border-gray-200 mb-3 block sm:hidden">
                        <!-- SearchBox -->
                        <form method="GET" action="{{ url('/') }}">
                            <div class="relative flex rounded-full shadow-sm">
                                <input type="text" name="keyword"
                                    placeholder="Search pins by title, description or tags"
                                    id="hs-trailing-button-add-on-with-icon-and-button"
                                    name="hs-trailing-button-add-on-with-icon-and-button"
                                    class="py-3 px-4 ps-11 block w-full border-gray-200 shadow-sm rounded-s-full text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                                <div
                                    class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                    <svg class="flex-shrink-0 size-4 text-gray-400 dark:text-neutral-500"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <path d="m21 21-4.3-4.3"></path>
                                    </svg>
                                </div>
                                <button type="submit"
                                    class="py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-e-full border border-gray-200 text-gray-500 hover:border-blue-600 hover:text-blue-600 disabled:opacity-50 disabled:pointer-events-none">Search</button>
                            </div>
                            <!-- End SearchBox -->
                        </form>
                    </div>
                    <div class="hs-tooltip [--trigger:click]">
                        <div class="hs-tooltip-toggle">
                            <a href="#" class="items-center gap-x-2 font-medium text-gray-500 hidden sm:flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-bell">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                                    <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
                                </svg>
                            </a>
                        </div>
                        <div class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible hidden opacity-0 transition-opacity absolute invisible z-10 max-w-xs bg-white border border-gray-100 text-start rounded-lg shadow-md"
                            role="tooltip">
                            <span class="py-3 px-4 block text-lg font-bold text-gray-800 border-b border-gray-200">🔔
                                Notification</span>
                            <div class="px-4 text-sm text-gray-600">
                                @if (Auth::check())
                                    <ul class="max-w-xs flex flex-col divide-y divide-gray-200">
                                        @if (Auth::user()->unreadNotifications->isEmpty())
                                            <li
                                                class="inline-flex items-center gap-x-2 py-3 text-sm font-medium text-gray-800">
                                                Tidak ada notifikasi
                                            </li>
                                        @else
                                            @foreach (Auth::user()->unreadNotifications as $notification)
                                                <li
                                                    class="inline-flex items-center gap-x-2 py-3 text-sm font-medium text-gray-800">
                                                    {{ App\Models\User::find($notification->data['liker_id'])->name }}
                                                    {{ $notification->data['message'] }}
                                                </li>
                                            @endforeach
                                        @endif
                                    @else
                                        <li
                                            class="inline-flex items-center gap-x-2 py-3 text-sm font-medium text-gray-800">
                                            Anda harus login terlebih dahulu
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                    <a href="#"
                        class="cursor-not-allowed items-center gap-x-2 font-medium text-gray-500 hidden sm:flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-message">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M8 9h8" />
                            <path d="M8 13h6" />
                            <path
                                d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12z" />
                        </svg>
                    </a>
                    @auth
                        <a href="#" class="text-gray-400 hidden sm:block">|</a>
                        <div class="hs-dropdown relative hidden sm:inline-flex">
                            <button type="button"
                                class="hs-dropdown-toggle flex items-center w-full text-gray-500 hover:text-gray-400 font-medium px-6 sm:px-0">
                                @if (Auth::user()->profile_photo_path)
                                    <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}"
                                        alt="{{ Auth::user()->username }}"
                                        class="w-9 h-9 rounded-full mr-2 border border-blue-200">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->username }}&background=DBEAFE&color=1E40AF&bold=true"
                                        alt="{{ Auth::user()->username }}" class="w-9 h-9 rounded-full mr-2">
                                @endif
                                <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </button>

                            <div style="z-index: 100"
                                class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg p-2 mt-2"
                                aria-labelledby="hs-dropdown-with-header">
                                <div class="py-3 px-5 -m-2 bg-gray-100 rounded-t-lg">
                                    <p class="text-sm text-gray-500">Signed in as</p>
                                    <p class="text-sm font-medium text-gray-800">{{ Auth::user()->username }}</p>
                                </div>
                                <div class="mt-2 py-2 first:pt-0 last:pb-0">
                                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                        href="{{ url('/user/' . Auth::user()->username) }}">
                                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                        </svg>
                                        Profile
                                    </a>
                                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                        href="{{ url('/profile') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="flex-shrink-0 size-4 icon icon-tabler icons-tabler-outline icon-tabler-lock">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z" />
                                            <path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" />
                                            <path d="M8 11v-4a4 4 0 1 1 8 0v4" />
                                        </svg>
                                        Account Management
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                            href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-logout">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                                <path d="M9 12h12l-3 -3" />
                                                <path d="M18 15l3 -3" />
                                            </svg>
                                            Logout
                                        </a>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="#" class="text-gray-400 hidden sm:block">|</a>
                            <a class="hidden sm:border-s sm:border-gray-300 py-2 px-3 sm:inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-blue-600 text-blue-600 hover:border-blue-500 hover:text-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:border-blue-500 dark:text-blue-500 dark:hover:text-blue-400 dark:hover:border-blue-400"
                                href="{{ url('/login') }}">Get Started</a>
                        </div>
                    @endauth
                </div>
        </nav>
    </header>
    <!-- ========== END HEADER ========== -->

    <!-- ========== MAIN CONTENT ========== -->
    <main id="content">
        <div class="w-full mx-auto bg-[#F9FAFB] min-h-screen">
            <div class="text-center">
                <h1 class="text-4xl sm:text-6xl font-bold text-gray-800 pt-10">
                    {{ $album->name }}
                </h1>
                <p class="mt-3 text-gray-600">
                    Discover collection of pins that handpicked by this user
                </p>
            </div>

            <div class="mx-auto px-4 pb-4 mt-12">
                <div class="bg-white rounded-xl shadow mx-auto px-4 py-4">
                    <div class="mt-3">
                        <div id="horizontal-alignment-1" aria-labelledby="horizontal-alignment-item-1">
                            <div class="mx-auto px-4 py-4">

                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2">
                                    @foreach ($album->pins as $index => $pin)
                                        @if ($index % 3 == 0)
                                            @if ($index != 0)
                                </div>
                                @endif
                                <div class="space-y-2">
                                    @endif
                                    <div class="relative group">
                                        <a href="{{ route('pins.show', $pin->id) }}">
                                            <img class="w-full h-auto object-cover rounded-lg max-h-[600px]"
                                                src="{{ asset('storage/pins/' . $pin->image_path) }}"
                                                alt="Image Description">
                                        </a>
                                        <div
                                            class="absolute flex bottom-2 left-2 bg-black bg-opacity-40 text-white p-2 rounded-lg">
                                            <img src="{{ Storage::url($pin->user->profile_photo_path) }}"
                                                alt="{{ $pin->user->username }}"
                                                class="w-8 h-8 rounded-full mr-2 my-auto">
                                            <div>
                                                <div class="font-semibold text-white my-auto hidden sm:block">
                                                    {{ \Illuminate\Support\Str::limit($pin->title, 25) }}
                                                </div>
                                                <div class="font-semibold text-white my-auto block sm:hidden text-sm">
                                                    {{ \Illuminate\Support\Str::limit($pin->title, 6) }}
                                                </div>
                                                <p class="text-xs text-gray-300">
                                                    {{ '@' . $pin->user->username }}
                                                </p>
                                            </div>
                                        </div>
                                        @auth
                                            <div class="hs-dropdown inline-flex absolute right-2 top-2">
                                                <form method="POST" action="{{ route('albums.removePin', $album) }}">
                                                    @csrf
                                                    <input type="hidden" name="pin_id" value="{{ $pin->id }}">
                                                    <button id="hs-dropdown-default" type="submit"
                                                        class="hs-dropdown-toggle py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-white bg-opacity-70 text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                                                        Remove from Album
                                                    </button>
                                                </form>
                                            </div>
                                        @endauth
                                        <form method="POST"
                                            action="{{ Auth::check() && Auth::user()->hasLikedPin($pin->id) ? route('unlike', $pin->id) : route('like', $pin->id) }}"
                                            class="absolute right-2 bottom-14 bg-black bg-opacity-40 text-white px-2 pt-2 pb-1 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            @csrf
                                            <button type="submit" class="m-0 p-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24"
                                                    fill="{{ Auth::check() && Auth::user()->hasLikedPin($pin->id) ? 'currentColor' : 'none' }}"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-heart">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
                                                </svg>
                                            </button>
                                        </form>
                                        <a href="{{ asset('storage/pins/' . $pin->image_path) }}" download
                                            class="absolute right-2 bottom-2 bg-black bg-opacity-40 text-white p-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-download">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                                <path d="M7 11l5 5l5 -5" />
                                                <path d="M12 4l0 12" />
                                            </svg>
                                        </a>
                                    </div>
                                    @if ($index == count($album->pins) - 1)
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>

</html>
