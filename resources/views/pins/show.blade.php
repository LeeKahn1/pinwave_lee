<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pin->title }} - Pins Pinwave</title>

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
            <div class="mx-auto px-4 py-4">
                <div class="sm:flex max-w-6xl mx-auto">
                    <!-- component -->
                    <section
                        class="text-gray-700
                          body-font overflow-hidden shadow-xl rounded-xl bg-white mx-auto">
                        <div class="container px-5 py-5 mx-auto rounded-lg">
                            <div class="mx-auto flex flex-wrap">
                                <img alt="ecommerce"
                                    class="lg:w-1/2 w-full object-cover object-center rounded-xl border border-gray-200"
                                    src="{{ asset('storage/pins/' . $pin->image_path) }}" alt="Image Description">
                                <div class="lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0">
                                    <div class="flex justify-between items-center">
                                        <a target="_blank" href="{{ $pin->link }}"
                                            class="text-sm title-font text-gray-500 tracking-widest">SOURCE LINK →</a>
                                        @php
                                            $reported = \App\Models\Report::where('user_id', auth()->id())
                                                ->where('pin_id', $pin->id)
                                                ->exists();
                                        @endphp

                                        @if ($reported)
                                            <form method="POST" action="/reports/{{ $pin->id }}">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="text-sm title-font text-gray-500 font-semibold">
                                                    🏳️ UNREPORT PINS
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="/reports">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                                <input type="hidden" name="pin_id" value="{{ $pin->id }}">
                                                <input type="hidden" name="reason" value="Inapropriate">

                                                <button type="submit"
                                                    class="text-sm title-font text-red-500 font-semibold">
                                                    🚩 REPORT PINS
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    <h1 class="text-gray-900 text-3xl title-font font-bold my-2">{{ $pin->title }}
                                    </h1>
                                    <div class="flex mb-4">
                                        <span class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-heart">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
                                            </svg>
                                            <span class="text-gray-600 ml-2">{{ $pin->likes->count() }} Likes</span>
                                        </span>
                                        <span class="flex ml-3 pl-3 py-2 border-l-2 border-gray-200">
                                            <a class="text-gray-500">
                                                <svg fill="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" class="w-5 h-5"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <a class="ml-2 text-gray-500">
                                                <svg fill="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" class="w-5 h-5"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <a class="ml-2 text-gray-500">
                                                <svg fill="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" class="w-5 h-5"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </span>
                                    </div>
                                    <p class="leading-relaxed">{{ $pin->description }}</p>
                                    <hr class="border-gray-300 my-4">
                                    <div>
                                        <section class="bg-white py-2 antialiased">
                                            <div class="max-w-2xl mx-auto">
                                                <div class="flex justify-between items-center mb-4">
                                                    <h2 class="text-md font-bold text-gray-900 ">
                                                        Comments</h2>
                                                </div>
                                                <form method="POST" action="{{ route('comments.store', $pin->id) }}"
                                                    class="mb-6">
                                                    @csrf
                                                    <div
                                                        class="py-2 px-4 mb-4 bg-white rounded-lg rounded-t-lg border border-gray-200 ">
                                                        <label for="comment" class="sr-only">Your comment</label>
                                                        <textarea id="comment" rows="1" name="content"
                                                            class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none "
                                                            placeholder="Write a comment..." required></textarea>
                                                    </div>
                                                    <button type="submit"
                                                        class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-blue-500 border border-blue-500 rounded-lg focus:ring-4 focus:ring-primary-200 hover:bg-primary-800">
                                                        Post comment
                                                    </button>
                                                </form>
                                                @foreach ($pin->comments as $comment)
                                                    <article class="py-1 text-base rounded-lg ">
                                                        <footer class="flex justify-between items-center mb-2">
                                                            <div class="flex items-center">
                                                                <p
                                                                    class="inline-flex items-center mr-3 text-sm text-gray-900  font-semibold">
                                                                    <img class="mr-2 w-6 h-6 rounded-full"
                                                                        src="{{ Storage::url($comment->user->profile_photo_path) }}"
                                                                        alt="Michael Gough">{{ $comment->user->username }}

                                                                    :
                                                                <p class="text-gray-500">{{ $comment->content }}</p>
                                                                </p>
                                                            </div>
                                                        </footer>
                                                    </article>
                                                @endforeach
                                            </div>
                                        </section>
                                    </div>
                                    <hr class="border-gray-300 mb-6 mt-2">
                                    <div class="flex justify-between">
                                        <div class="flex">
                                            <img src="{{ Storage::url($pin->user->profile_photo_path) }}"
                                                alt="{{ $pin->user->username }}"
                                                class="w-12 h-12 rounded-full mr-2.5 my-auto">
                                            <div class="my-auto">
                                                <div class="font-semibold text-black mb-0.5">
                                                    {{ '@' . $pin->user->username }}
                                                </div>
                                                <p class="text-xs text-gray-400">
                                                    {{ $pin->user->followers->count() }} followers
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex my-auto">
                                            @if (Auth::user()->id != $pin->user->id)
                                                <form method="POST"
                                                    action="{{ Auth::user()->following->contains($pin->user->id) ? route('unfollow', $pin->user->id) : route('follow', $pin->user->id) }}">
                                                    @csrf
                                                    @if (Auth::user()->following->contains($pin->user->id))
                                                        @method('DELETE')
                                                    @endif
                                                    <button type="submit"
                                                        class="text-white bg-blue-500 border-0 py-2 px-6 focus:outline-none hover:bg-blue-600 rounded-lg font-bold">
                                                        {{ Auth::user()->following->contains($pin->user->id) ? 'Unfollow' : 'Follow' }}
                                                    </button>
                                                </form>
                                            @endif
                                            <form method="POST"
                                                action="{{ $pin->isLikedBy(auth()->user()) ? route('unlike', $pin->id) : route('like', $pin->id) }}">
                                                @csrf
                                                <button type="submit"
                                                    class="rounded-full w-10 h-10 bg-gray-200 p-0 border-0 inline-flex items-center justify-center text-gray-500 ml-4">
                                                    <svg fill="{{ $pin->isLikedBy(auth()->user()) ? 'red' : 'currentColor' }}"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                                        <path
                                                            d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <div class="mx-auto px-4 py-4 mt-4">
                <h2 class="text-3xl text-center mt-2 mb-10 font-bold">Lainnya dari {{ $pin->user->username }} </h2>
                @if (!isset($otherPins) || count($otherPins) === 0)
                    <div class="flex justify-center h-screen">
                        <div class="w-full flex flex-wrap justify-center gap-10">
                            <div class="mt-24 gap-4 w-60">
                                <svg class="mx-auto" xmlns="http://www.w3.org/2000/svg" width="128"
                                    height="124" viewBox="0 0 128 124" fill="none">
                                    <g filter="url(#filter0_d_14133_718)">
                                        <path
                                            d="M4 61.0062C4 27.7823 30.9309 1 64.0062 1C97.0319 1 124 27.7699 124 61.0062C124 75.1034 119.144 88.0734 110.993 98.3057C99.7572 112.49 82.5878 121 64.0062 121C45.3007 121 28.2304 112.428 17.0071 98.3057C8.85599 88.0734 4 75.1034 4 61.0062Z"
                                            fill="#F9FAFB" />
                                    </g>
                                    <path
                                        d="M110.158 58.4715H110.658V57.9715V36.9888C110.658 32.749 107.226 29.317 102.986 29.317H51.9419C49.6719 29.317 47.5643 28.165 46.3435 26.2531L46.342 26.2509L43.7409 22.2253L43.7404 22.2246C42.3233 20.0394 39.8991 18.7142 37.2887 18.7142H20.8147C16.5749 18.7142 13.1429 22.1462 13.1429 26.386V57.9715V58.4715H13.6429H110.158Z"
                                        fill="#EEF2FF" stroke="#A5B4FC" />
                                    <path
                                        d="M49 20.2142C49 19.6619 49.4477 19.2142 50 19.2142H106.071C108.281 19.2142 110.071 21.0051 110.071 23.2142V25.6428H53C50.7909 25.6428 49 23.8519 49 21.6428V20.2142Z"
                                        fill="#A5B4FC" />
                                    <circle cx="1.07143" cy="1.07143" r="1.07143"
                                        transform="matrix(-1 0 0 1 36.1429 23.5)" fill="#4F46E5" />
                                    <circle cx="1.07143" cy="1.07143" r="1.07143"
                                        transform="matrix(-1 0 0 1 29.7144 23.5)" fill="#4F46E5" />
                                    <circle cx="1.07143" cy="1.07143" r="1.07143"
                                        transform="matrix(-1 0 0 1 23.2858 23.5)" fill="#4F46E5" />
                                    <path
                                        d="M112.363 95.459L112.362 95.4601C111.119 100.551 106.571 104.14 101.323 104.14H21.8766C16.6416 104.14 12.0808 100.551 10.8498 95.4592C10.8497 95.4591 10.8497 95.459 10.8497 95.459L1.65901 57.507L1.65896 57.5068C0.0470794 50.8383 5.09094 44.4286 11.9426 44.4286H111.257C118.108 44.4286 123.166 50.8371 121.541 57.5069L112.363 95.459ZM112.363 95.459L121.541 57.5077L112.363 95.459Z"
                                        fill="white" stroke="#E5E7EB" />
                                    <path
                                        d="M65.7893 82.4286C64.9041 82.4286 64.17 81.6945 64.17 80.7877C64.17 77.1605 58.686 77.1605 58.686 80.7877C58.686 81.6945 57.9519 82.4286 57.0451 82.4286C56.1599 82.4286 55.4258 81.6945 55.4258 80.7877C55.4258 72.8424 67.4302 72.8639 67.4302 80.7877C67.4302 81.6945 66.6961 82.4286 65.7893 82.4286Z"
                                        fill="#4F46E5" />
                                    <path
                                        d="M79.7153 68.5462H72.9358C72.029 68.5462 71.2949 67.8121 71.2949 66.9053C71.2949 66.0201 72.029 65.286 72.9358 65.286H79.7153C80.6221 65.286 81.3562 66.0201 81.3562 66.9053C81.3562 67.8121 80.6221 68.5462 79.7153 68.5462Z"
                                        fill="#4F46E5" />
                                    <path
                                        d="M49.9204 68.546H43.1409C42.2341 68.546 41.5 67.8119 41.5 66.9051C41.5 66.0198 42.2341 65.2858 43.1409 65.2858H49.9204C50.8056 65.2858 51.5396 66.0198 51.5396 66.9051C51.5396 67.8119 50.8056 68.546 49.9204 68.546Z"
                                        fill="#4F46E5" />
                                    <circle cx="107.929" cy="91.0001" r="18.7143" fill="#EEF2FF"
                                        stroke="#E5E7EB" />
                                    <path
                                        d="M115.161 98.2322L113.152 96.2233M113.554 90.1965C113.554 86.6461 110.676 83.7679 107.125 83.7679C103.575 83.7679 100.697 86.6461 100.697 90.1965C100.697 93.7469 103.575 96.6251 107.125 96.6251C108.893 96.6251 110.495 95.9111 111.657 94.7557C112.829 93.5913 113.554 91.9786 113.554 90.1965Z"
                                        stroke="#4F46E5" stroke-width="1.6" stroke-linecap="round" />
                                    <defs>
                                        <filter id="filter0_d_14133_718" x="2" y="0" width="124" height="124"
                                            filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                            <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                            <feColorMatrix in="SourceAlpha" type="matrix"
                                                values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                                                result="hardAlpha" />
                                            <feOffset dy="1" />
                                            <feGaussianBlur stdDeviation="1" />
                                            <feComposite in2="hardAlpha" operator="out" />
                                            <feColorMatrix type="matrix"
                                                values="0 0 0 0 0.0627451 0 0 0 0 0.0941176 0 0 0 0 0.156863 0 0 0 0.05 0" />
                                            <feBlend mode="normal" in2="BackgroundImageFix"
                                                result="effect1_dropShadow_14133_718" />
                                            <feBlend mode="normal" in="SourceGraphic"
                                                in2="effect1_dropShadow_14133_718" result="shape" />
                                        </filter>
                                    </defs>
                                </svg>
                                <div>
                                    <h2 class="text-center text-black text-base font-semibold leading-relaxed pb-1">
                                        User ini belum mengupload pins</h2>
                                    <p class="text-center text-black text-sm font-normal leading-snug pb-4">
                                        Coba cari
                                        user lain yang <br />sudah upload ya! </p>
                                    <div class="flex gap-3">
                                        <a href="{{ url('/') }}"
                                            class="w-full px-3 py-2 rounded-full border text-center border-gray-300 text-gray-900 text-xs font-semibold leading-4">
                                            Kembali </a>
                                        <a href="{{ url('/pins/create') }}"
                                            class="w-full px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-center transition-all duration-500 rounded-full text-white text-xs font-semibold leading-4">
                                            Tambah Pin </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2">
                        @foreach ($otherPins as $index => $pin)
                            @if ($index % 3 == 0)
                                @if ($index != 0)
                    </div>
                @endif
                <div class="space-y-2">
                    @endif
                    <div class="relative group">
                        <a href="{{ route('pins.show', $pin->id) }}">
                            <img class="w-full h-auto object-cover rounded-lg max-h-[600px]"
                                src="{{ asset('storage/pins/' . $pin->image_path) }}" alt="Image Description">
                        </a>
                        <div class="absolute flex bottom-2 left-2 bg-black bg-opacity-40 text-white p-2 rounded-lg">
                            <img src="{{ Storage::url($pin->user->profile_photo_path) }}"
                                alt="{{ $pin->user->username }}" class="w-8 h-8 rounded-full mr-2 my-auto">
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
                                <button id="hs-dropdown-default" type="button"
                                    class="hs-dropdown-toggle py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-white bg-opacity-70 text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                                    Save Album
                                    <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </button>

                                <div style="z-index: 100;"
                                    class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg p-2 mt-2  after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full"
                                    aria-labelledby="hs-dropdown-default">
                                    @if (Auth::check() && Auth::user()->albums->count() > 0)
                                        @foreach (Auth::user()->albums as $album)
                                            <form method="POST"
                                                action="{{ route('saveToAlbum', ['albumId' => $album->id]) }}">
                                                @csrf
                                                <input type="hidden" name="pin_id" value="{{ $pin->id }}">
                                                <button type="submit"
                                                    class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100">
                                                    {{ $album->name }}
                                                </button>
                                            </form>
                                        @endforeach
                                    @else
                                        <div class="py-2 px-3 text-sm text-gray-800">
                                            Tidak ada album
                                        </div>
                                    @endif
                                </div>
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
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-download">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                <path d="M7 11l5 5l5 -5" />
                                <path d="M12 4l0 12" />
                            </svg>
                        </a>
                    </div>
                    @if ($index == count($otherPins) - 1)
                </div>
                @endif
                @endforeach
                @endif
            </div>
        </div>


    </main>
    <!-- ========== END MAIN CONTENT ========== -->

</body>

</html>
