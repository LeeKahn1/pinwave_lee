<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Create Pins - Pinwave</title>

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
                    <a class="font-semibold text-gray-500 hover:text-gray-400 sm:py-6"
                        href="{{ url('/') }}">Home</a>
                    <a class="font-semibold text-blue-600 sm:py-6" href="{{ url('/pins/create') }}"
                        aria-current="page">Create Pin</a>
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
        <div class="w-full mx-auto">
            <div class="mx-auto px-4 py-4">
                <!-- Hire Us -->
                <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
                    <!-- Grid -->
                    <div class="grid md:grid-cols-2 items-center gap-12">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800 sm:text-4xl lg:text-5xl lg:leading-tight">
                                ✏️ Create Pins
                            </h1>
                            <p class="mt-1 md:text-lg text-gray-800">
                                Share your arts, picture edits, illustration and more with the world.
                            </p>

                            <div class="mt-8">
                                <h2 class="text-lg font-semibold text-gray-800">
                                    What can I expect?
                                </h2>

                                <ul class="mt-2 space-y-2">
                                    <li class="flex space-x-3">
                                        <svg class="flex-shrink-0 mt-0.5 size-5 text-gray-600"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                        <span class="text-gray-600">
                                            Community of creators
                                        </span>
                                    </li>

                                    <li class="flex space-x-3">
                                        <svg class="flex-shrink-0 mt-0.5 size-5 text-gray-600"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                        <span class="text-gray-600">
                                            Make your ideas come to life
                                        </span>
                                    </li>

                                    <li class="flex space-x-3">
                                        <svg class="flex-shrink-0 mt-0.5 size-5 text-gray-600"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                        <span class="text-gray-600">
                                            Feedback from the community
                                        </span>
                                    </li>
                                </ul>
                            </div>

                            <div class="mt-10 flex items-center gap-x-5">
                                <!-- Avatar Group -->
                                <div class="flex -space-x-2">
                                    <img class="inline-block size-8 rounded-full ring-2 ring-white"
                                        src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=320&h=320&q=80"
                                        alt="Image Description">
                                    <img class="inline-block size-8 rounded-full ring-2 ring-white"
                                        src="https://images.unsplash.com/photo-1531927557220-a9e23c1e4794?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2.5&w=320&h=320&q=80"
                                        alt="Image Description">
                                    <img class="inline-block size-8 rounded-full ring-2 ring-white"
                                        src="https://images.unsplash.com/photo-1541101767792-f9b2b1c4f127?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=3&w=320&h=320&q=80"
                                        alt="Image Description">
                                    <span
                                        class="inline-flex justify-center items-center size-8 rounded-full bg-blue-600 text-white ring-2 ring-white">
                                        <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" width="16"
                                            height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path
                                                d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                        </svg>
                                    </span>
                                </div>
                                <!-- End Avatar Group -->
                                <span class="text-sm text-gray-500">
                                    More than 200+ users registered
                                </span>
                            </div>
                        </div>
                        <!-- End Col -->

                        <div class="relative">
                            <!-- Card -->
                            <div class="flex flex-col border rounded-xl p-4 sm:p-6 lg:p-10">
                                <h2 class="text-xl font-semibold text-gray-800">
                                    Create Pin
                                </h2>

                                <form method="POST" action="{{ route('pins.store') }}"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="mt-6 grid gap-4 lg:gap-6">
                                        <div class="space-y-2">
                                            <label for="image"
                                                class="inline-block text-sm font-medium text-gray-800 mt-2.5 ">
                                                Upload image
                                            </label>

                                            <label for="image"
                                                class="group p-4 sm:p-7 block cursor-pointer text-center border-2 border-dashed border-gray-200 rounded-lg focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2">
                                                <input id="image" name="image" type="file" accept="image/*"
                                                    class="sr-only">
                                                <svg class="size-10 mx-auto text-gray-400 "
                                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2z" />
                                                    <path
                                                        d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z" />
                                                </svg>
                                                <span class="mt-2 block text-sm text-gray-800 ">
                                                    Browse your device or <span
                                                        class="group-hover:text-blue-700 text-blue-600">drag 'n
                                                        drop'</span>
                                                </span>
                                                <span class="mt-1 block text-xs text-gray-500">
                                                    Maximum file size is 2 MB
                                                </span>
                                            </label>
                                        </div>

                                        <div>
                                            <label for="title"
                                                class="block mb-2 text-sm text-gray-700 font-medium">Title</label>
                                            <input type="text" name="title" id="title"
                                                placeholder="Enter title"
                                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                                        </div>

                                        <div>
                                            <label for="description"
                                                class="block mb-2 text-sm text-gray-700 font-medium">Description</label>
                                            <textarea id="description" name="description" rows="4" placeholder="Enter description about this pin..."
                                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"></textarea>
                                        </div>

                                        <div>
                                            <label for="link"
                                                class="block mb-2 text-sm text-gray-700 font-medium">Link</label>
                                            <input type="text" name="link" id="link"
                                                placeholder="Enter links to source"
                                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                                        </div>

                                        <div>
                                            @php
                                                $albums = auth()->user()->albums;
                                            @endphp

                                            <label for="af-submit-app-category"
                                                class="inline-block text-sm font-medium text-gray-800 mb-2 ">
                                                Album
                                            </label>

                                            <select name="album_id" id="af-submit-app-category"
                                                class="py-3 px-4 pe-9 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                                                onchange="this.options[0].disabled = this.value === 'none';">
                                                <option value="none" disabled selected>Select Album</option>
                                                @foreach ($albums as $album)
                                                    <option value="{{ $album->id }}">{{ $album->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label for="tags"
                                                class="block mb-2 text-sm text-gray-700 font-medium">Tags</label>
                                            <input type="text" name="tags" id="tags"
                                                placeholder="Enter tags separated by commas"
                                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                                        </div>

                                    </div>

                                    <div class="mt-6 grid">
                                        <button type="submit"
                                            class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">Create
                                            Pins</button>
                                    </div>
                                </form>
                                <!-- End Grid -->

                                <div class="mt-3 text-center">
                                    <p class="text-sm text-gray-500">
                                        Your pins will be public and can be viewed by anyone.
                                    </p>
                                </div>
                            </div>
                            <!-- End Card -->
                        </div>
                        <!-- End Col -->
                    </div>
                    <!-- End Grid -->
                </div>
                <!-- End Hire Us -->
            </div>
        </div>
    </main>
    <!-- ========== END MAIN CONTENT ========== -->
</body>

</html>
