<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }}</title>

    <link rel="icon" href="{{ asset('resources/img/rweb_icon.png') }}" type="image/x-icon">

    {{-- <link rel="stylesheet" href=" {{ asset('build/assets/app.css') }}">
    <script src="{{ asset('build/assets/app.js') }}"></script> --}}

    {{-- <link rel="stylesheet" href=" {{ 'resources/css/app.css' }}"> --}}
    {{-- <script src="{{ 'resources/js/app.js' }}"></script> --}}

    @vite('resources/css/app.css')

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.7/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.7/dist/ScrollTrigger.min.js"></script>

    {{-- modal script --}}
    <link href="https://cdn.jsdelivr.net/npm/pagedone@1.2.2/src/css/pagedone.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/pagedone@1.2.2/src/js/pagedone.js"></script>

    {{-- font url --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- hedvig font --}}
    <link href="https://fonts.googleapis.com/css2?family=Hedvig+Letters+Sans&display=swap" rel="stylesheet">

    {{-- swiper --}}
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Pusher Beams & Laravel Echo -->
    <script src="https://js.pusher.com/beams/1.0/push-notifications-cdn.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>

    <!-- Include Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">

    <!-- Include jQuery (required for Toastr) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Include Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        Pusher.logToConsole = true; // Debugging, remove in production

        console.log('hello yo!');

        var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            forceTLS: true
        });

        var user_id = "{{ auth()->id() }}"; // Get logged-in user's ID

        var channel = pusher.subscribe("public-notifications");

        // ✅ Listen only to events specific to this user
        channel.bind(`user-notification-${user_id}`, function(data) {
            let audio = new Audio('/resources/sfx/notification_sound_sfx.ogg');
            audio.play();

            if (/declined/i.test(data.message)) {
                toastr.error(data.message);
            } else if (/approved/i.test(data.message)) {
                toastr.success(data.message);
            } else {

                toastr.success(data.message);
            }
        });

        alert(user_id)
    </script>
</head>



<body class="text-[#1f2835] tracking-wide bg-gray-100">

    {{-- guest layout --}}
    @if (Request::routeIs('show.login*') || Request::routeIs('show.register*'))
        <div class="md:!grid md:!grid-cols-12 h-[calc(100vh)] w-full overflow-auto">
            <section class="md:col-span-8 md:h-[calc(100vh)] w-full bg-white">
                {{ $slot }}
            </section>

            <section class="md:col-span-4 md:h-[calc(100vh)] w-full h-[calc(100vh-50%)] md:sticky md:top-0">
                @if (Request::routeIs('show.register'))
                    <x-form.option imgPath="register.png" title="Have an account?" routePath="show.login"
                        desc="Stay on top of your schedule!" btnLabel="Login here." />

                    {{-- register button --}}
                @elseif (Request::routeIs('show.login'))
                    <x-form.option imgPath="login.png" title="New Intern?" routePath="show.register"
                        desc="Sign up to keep track of your daily attendance." btnLabel="Register here." />
                @endif
        </div>

        {{-- intern layout --}}
    @elseif (Request::routeIs('users.dashboard*') ||
            Request::routeIs('users.settings*') ||
            Request::routeIs('users.dtr*') ||
            Request::routeIs('users.request*'))
        <div class="h-full w-full lg:grid lg:grid-cols-12">
            <section class="sticky lg:hidden top-0 w-full bg-white shadow-md h-auto py-4 z-50">
                <div class="flex items-center justify-between w-full lg:px-10 px-5 gap-5">
                    <section class="grid grid-cols-3 w-full">
                        <div class="col-span-1 flex items-center justify-start w-full">
                            <button id="intern-menu-toggle" class="lg:hidden p-2 border rounded-md w-fit h-fit">
                                ☰
                            </button>
                        </div>
                        <div class="col-span-1 w-full flex items-center justify-center">
                            <x-logo />
                        </div>
                    </section>
                </div>
            </section>

            @php
                $profile = \App\Models\Profile::where('id', Auth::user()->profile_id)->first();
                $file = \App\Models\File::where('id', $profile->file_id)->first();
            @endphp

            <!-- Sidebar Menu (Hidden on Large Screens) -->
            <aside id="mobile-menu"
                class="fixed top-22 left-0 mt-0 w-64 h-[calc(100vh-4rem)] bg-white shadow-md transform -translate-x-full transition-transform lg:hidden overflow-auto z-50 flex flex-col justify-between py-5">

                <nav class="w-full flex flex-col gap-10">
                    <section class="w-full flex flex-col gap-2 justify-center items-center px-7">
                        <div class="w-auto h-auto">
                            <div class="h-24 w-24 shadow-md border border-[#F57D11] rounded-full overflow-hidden">
                                <x-image path="{{ $file->path . '?t=' . time() }}" className="h-full w-full" />
                            </div>
                        </div>
                        <h1 class="font-bold text-lg capitalize text-center text-ellipsis">
                            {{ Auth::user()->firstname }}
                            {{ substr(Auth::user()->middlename, 0, 1) }}. {{ Auth::user()->lastname }}</h1>
                        <p class="text-[#F53C11] text-center -mt-2">{{ Auth::user()->email }}</p>

                    </section>

                    <section class="w-full border-y border-gray-100 py-5">
                        <x-sidebar-menu routeName="users.dashboard" label="Dashboard" icon="akar-icons--dashboard">
                            <span class="akar-icons--dashboard"></span>
                            <p>Dashboard</p>
                        </x-sidebar-menu>
                        <x-sidebar-menu routeName="users.dtr" label="DTR" icon="mingcute--paper-line">
                            <span class="mingcute--paper-line"></span>
                            <p>DTR</p>
                        </x-sidebar-menu>
                        <x-sidebar-menu routeName="users.request" label="Request" icon="ph--hand-deposit">
                            <span class="ph--hand-deposit"></span>
                            <p>Request</p>
                        </x-sidebar-menu>
                        <x-sidebar-menu routeName="users.settings" label="Settings" icon="solar--settings-linear">
                            <span class="solar--settings-linear"></span>
                            <p>Settings</p>
                        </x-sidebar-menu>
                    </section>
                </nav>

                <section class="pt-5 w-full">
                    <x-form.container routeName="logout" className="flex items-center justify-center">
                        @csrf
                        <button
                            class="flex items-center opacity-100 gap-1 w-full px-8 py-5 font-semibold bg-[#F53C11] hover:bg-[#F53C11]/80 text-white animate-transition"><span
                                class="material-symbols--logout-rounded"></span>Logout</button>
                    </x-form.container>
                </section>
            </aside>

            <!-- Left Sidebar (Sticky on Large Screens) -->
            <aside
                class="hidden lg:flex flex-col justify-between items-center col-span-3 bg-white shadow-xl sticky top-0 h-[calc(100vh)] overflow-auto py-5">

                <nav class="w-full flex flex-col gap-10">

                    <div class="px-7 pt-5 w-full flex justify-center">
                        <x-logo />
                    </div>

                    <section class="w-full flex flex-col gap-2 justify-center items-center px-7">
                        <div class="w-auto h-auto">
                            <div class="h-32 w-32 shadow-md border border-[#F57D11] rounded-full overflow-hidden">
                                <x-image path="{{ $file->path . '?t=' . time() }}" className="w-full h-full" />
                            </div>
                        </div>
                        <h1 class="font-bold text-lg capitalize text-center text-ellipsis">
                            {{ Auth::user()->firstname }}
                            {{ substr(Auth::user()->middlename, 0, 1) }}. {{ Auth::user()->lastname }}</h1>
                        <p class="text-[#F53C11] text-center -mt-2">{{ Auth::user()->email }}</p>

                    </section>

                    <section class="w-full border-y border-gray-100 py-5">
                        <x-sidebar-menu routeName="users.dashboard" label="Dashbaord" icon="akar-icons--dashboard">
                            <span class="akar-icons--dashboard"></span>
                            <p>Dashboard</p>
                        </x-sidebar-menu>
                        <x-sidebar-menu routeName="users.dtr" label="DTR" icon="mingcute--paper-line">
                            <span class="mingcute--paper-line"></span>
                            <p>DTR</p>
                        </x-sidebar-menu>
                        <x-sidebar-menu routeName="users.request" label="Request" icon="ph--hand-deposit">
                            <span class="ph--hand-deposit"></span>
                            <p>Request</p>
                        </x-sidebar-menu>
                        <x-sidebar-menu routeName="users.settings" label="Settings" icon="solar--settings-linear">
                            <span class="solar--settings-linear"></span>
                            <p>Settings</p>
                        </x-sidebar-menu>
                    </section>
                </nav>

                <section class="pt-5 w-full">
                    <x-form.container routeName="logout" className="flex items-center justify-center">
                        @csrf
                        <button
                            class="flex items-center opacity-100 gap-1 w-full px-8 py-5 font-semibold bg-[#F53C11] hover:bg-[#F53C11]/80 text-white animate-transition"><span
                                class="material-symbols--logout-rounded"></span>Logout</button>
                    </x-form.container>
                </section>
            </aside>

            <!-- Main Content (Auto Scroll) -->
            <main
                class="col-span-9 overflow-auto w-full lg:!h-[calc(100vh)] h-[calc(100vh-4rem)] bg-gray-100 lg:!p-10 p-5">
                {{ $slot }}
            </main>
        </div>

        <script>
            const menuToggle = document.getElementById("intern-menu-toggle");
            const mobileMenu = document.getElementById("mobile-menu");

            menuToggle.addEventListener("click", () => {
                mobileMenu.classList.toggle("-translate-x-full");
            });

            // swiper
            var swiper = new Swiper(".progress-slide-carousel", {
                loop: true,
                fraction: true,
                autoplay: {
                    delay: 1200,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: ".progress-slide-carousel .swiper-pagination",
                    type: "progressbar",
                },
            });

            document.addEventListener("DOMContentLoaded", function() {
                const dropdownToggle = document.getElementById("dropdown-toggle");
                const dropdownMenu = document.getElementById("dropdown-menu");

                // Toggle dropdown visibility on button click
                dropdownToggle.addEventListener("click", function() {
                    dropdownMenu.classList.toggle("hidden");
                });

                // Close dropdown when clicking outside
                document.addEventListener("click", function(event) {
                    if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
                        dropdownMenu.classList.add("hidden");
                    }
                });
            });
        </script>

        {{-- admin layout --}}
    @elseif (Request::routeIs('admin*'))
        @props(['breadcumb' => '', 'page' => ''])
        
        <main class="grid grid-cols-12 w-full h-auto">
            <aside class="col-span-3 h-[calc(100vh)] sticky top-0 w-full overflow-auto p-5 shadow-xl bg-white">
                <div class="flex items-center gap-5 justify-start">
                    <span class="mynaui--menu w-7 h-7"></span>
                    <img src="{{ asset('image/rweb_logo.png') }}" class="w-auto h-16">
                </div>
                <div class="my-7 bg-[#f56d11] h-[0.1px] rounded-full w-full"></div>

                {{-- admin front-end navbar --}}
                @if (Request::routeIs('admin.front-end*'))
                    <x-sidebar-menu icon="ic--baseline-space-dashboard" label="Dashboard"
                        routeName="admin.front-end.dashboard" />
                    <x-sidebar-menu icon="mdi--file-cog" label="Project Development"
                        routeName="admin.front-end.project-development" />
                    <x-sidebar-menu icon="fluent--clipboard-text-edit-48-filled" label="Revision Checklist"
                        routeName="admin.front-end.revision-checklist" />
                    <x-sidebar-menu icon="ic--baseline-discount" label="Promotions"
                        routeName="admin.front-end.promotions" />
                    <x-sidebar-menu icon="streamline--manual-book-solid" label="Instruction Manual"
                        routeName="admin.front-end.instructions-manual" />
                    <x-sidebar-menu icon="fa--user" label="Profile" routeName="admin.front-end.profile" />

                    {{-- admin dtr navbar --}}
                @elseif (Request::routeIs('admin.dtr*'))
                    <x-sidebar-menu icon="ic--baseline-space-dashboard" label="Dashboard"
                        routeName="admin.dtr.dashboard" />
                    <x-sidebar-menu icon="fluent--clipboard-text-edit-48-filled" label="Approvals"
                        routeName="admin.dtr.approvals" />
                    <x-sidebar-menu icon="fa--users" label="Interns" routeName="admin.dtr.interns" />
                    <x-sidebar-menu icon="mdi--clipboard-text-history" label="History"
                        routeName="admin.dtr.history" />
                    <x-sidebar-menu icon="ic--round-school" label="Schools" routeName="admin.dtr.schools" />
                    <x-sidebar-menu icon="fa--user" label="Profile" routeName="admin.dtr.profile" />
                @endif

            </aside>
            <section class="col-span-9 h-auto w-full bg-gray-100 pb-10">
                <div class="px-16 pt-16">
                    <headers class="w-full flex items-end justify-between">
                        
                        <x-admin.page-title breadcumb="{{ $breadcumb }}" page="{{ $page }}" />

                        <section class="fixed z-40 right-0 top-0">
                            <x-admin.mini-profile />
                        </section>
                    </headers>
                </div>

                {{ $slot }}
            </section>
        </main>

        {{-- admin dtr layout --}}
    @elseif (Request::routeIs('admin.dashboard*') ||
            Request::routeIs('admin.users*') ||
            Request::routeIs('admin.histories*') ||
            Request::routeIs('admin.profile*') ||
            Request::routeIs('admin.schools*') ||
            Request::routeIs('admin.approvals*'))
        <div class="h-full w-full lg:grid lg:grid-cols-12">

            <!-- Sidebar Menu (Hidden on Large Screens) -->
            <aside id="mobile-menu"
                class="fixed top-22 left-0 mt-20 w-64 h-[calc(100vh-5rem)] bg-white shadow-md transform -translate-x-full transition-transform lg:hidden overflow-auto z-50">
                <div class="px-5 pt-7 w-full">
                    <x-logo />
                </div>
                <nav class="mt-5">
                    <x-sidebar-menu icon="akar-icons--dashboard" label="Dashboard" routeName="admin.dtr.dashboard" />
                    {{-- <x-sidebar-menu route="admin.approvals">
                        <div class="w-auto h-auto flex items-center"><span class="lucide--check-check"></span>
                        </div>
                        <p>Approvals</p>
                    </x-sidebar-menu>
                    <x-sidebar-menu route="admin.users">
                        <div class="w-auto h-auto flex items-center"><span class="cuida--users-outline"></span>
                        </div>
                        <p>Users</p>
                    </x-sidebar-menu>
                    <x-sidebar-menu route="admin.histories">
                        <div class="w-auto h-auto flex items-center"><span
                                class="material-symbols--history-rounded w-6 h-6"></span></div>
                        <p>History</p>
                    </x-sidebar-menu>
                    <x-sidebar-menu route="admin.schools">
                        <div class="w-auto h-auto flex items-center"><span
                                class="material-symbols--school-outline-rounded !w-6 !h-6"></span></div>
                        <p>Schools</p>
                    </x-sidebar-menu>
                    <x-sidebar-menu route="admin.profile">
                        <div class="w-auto h-auto flex items-center"><span class="cuida--user-outline"></span>
                        </div>
                        <p>Profile</p>
                    </x-sidebar-menu> --}}
                </nav>
            </aside>

            <!-- Left Sidebar (Sticky on Large Screens) -->
            <aside
                class="hidden lg:block col-span-2 bg-white shadow-xl sticky top-0 h-[calc(100vh)] overflow-auto py-5">
                <div class="px-5 w-full">
                    <x-logo />
                </div>
                <!-- Navigation -->
                <nav class="mt-10">
                    <x-sidebar-menu icon="akar-icons--dashboard" label="Dashboard" routeName="admin.dtr.dashboard" />
                    {{-- <x-sidebar-menu route="admin.approvals">
                        <div class="w-auto h-auto flex items-center"><span class="lucide--check-check"></span>
                        </div>
                        <p>Approvals</p>
                    </x-sidebar-menu>
                    <x-sidebar-menu route="admin.users">
                        <div class="w-auto h-auto flex items-center"><span class="cuida--users-outline"></span>
                        </div>
                        <p>Users</p>
                    </x-sidebar-menu>
                    <x-sidebar-menu route="admin.histories">
                        <div class="w-auto h-auto flex items-center"><span
                                class="material-symbols--history-rounded w-6 h-6"></span></div>
                        <p>History</p>
                    </x-sidebar-menu>
                    <x-sidebar-menu route="admin.schools">
                        <div class="w-auto h-auto flex items-center"><span
                                class="material-symbols--school-outline-rounded !w-6 !h-6"></span></div>
                        <p>Schools</p>
                    </x-sidebar-menu>
                    <x-sidebar-menu route="admin.profile">
                        <div class="w-auto h-auto flex items-center"><span class="cuida--user-outline"></span>
                        </div>
                        <p>Profile</p>
                    </x-sidebar-menu> --}}
                </nav>
            </aside>

            <!-- Main Content (Auto Scroll) -->
            <main class="col-span-10 overflow-auto w-full h-[calc(100vh)] bg-gray-100">
                <section class="sticky top-0 w-full bg-white shadow-md h-auto py-4 z-50">
                    <div class="flex items-center justify-between w-full lg:px-10 px-5 gap-5">
                        <section class="flex items-center gap-4">
                            <button id="menu-toggle" class="lg:hidden p-2 border rounded-md">
                                ☰
                            </button>
                            @if (Request::routeIs('admin.dashboard*'))
                                <x-page-title title="Dashboard" />
                            @elseif (Request::routeIs('admin.approvals*'))
                                <x-page-title title="Approvals" />
                            @elseif (Request::routeIs('admin.users*'))
                                <x-page-title title="Users" />
                            @elseif (Request::routeIs('admin.histories*'))
                                <x-page-title title="History" />
                            @elseif (Request::routeIs('admin.schools*'))
                                <x-page-title title="Schools" />
                            @elseif (Request::routeIs('admin.profile*'))
                                <x-page-title title="Profile" />
                            @endif
                        </section>

                        <section class="flex items-center gap-2">
                            <div class="dropdown relative inline-flex self-center">
                                <button type="button" id="dropdown-notification"
                                    class="dropdown-notification w-10 h-10 relative text-gray-500 p-2 rounded-full hover:bg-gray-100 cursor-pointer">
                                    <span class="mi--notification w-full h-full relative"></span>
                                    @if ($notifications->where('is_read', 0)->count())
                                        {{-- <div
                                            class="absolute top-0 right-0 w-5 h-5 rounded-full bg-[#F53C11] p-1 text-center flex items-center justify-center text-white">
                                            <span
                                                class="text-[10px] font-semibold m-auto">{{ $notifications->where('is_read', 0)->count() }}</span>
                                        </div> --}}
                                        <div class="absolute top-0 right-0">
                                            <div
                                                class=" w-5 h-5 rounded-full bg-[#F53C11] p-1 text-center flex items-center justify-center text-white">
                                                <p class="text-[10px] font-semibold">
                                                    {{ $notifications->where('is_read', 0)->count() }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </button>

                                <div id="dropdown-show-notification"
                                    class="dropdown-menu-notification hidden rounded-lg shadow-lg border border-gray-300 bg-white absolute top-full lg:-right-40 -right-20 mt-2 md:w-[600px] sm:w-[400px] w-[300px] z-20">

                                    <!-- Header -->
                                    <div class="px-4 py-3 flex justify-between items-center text-[#F57D11]">
                                        <h2 class="text-base font-semibold">
                                            Notifications <span id="notification-count">
                                                @if ($notifications->where('is_read', 0)->count() != 0)
                                                    ({{ count($notifications->where('is_read', 0)) }})
                                                @endif
                                            </span>
                                        </h2>
                                    </div>

                                    <!-- Tabs -->
                                    <div class="flex border-b text-sm">
                                        <button id="tab-all"
                                            class="tab-btn px-4 py-2 text-[#F57D11] border-[#F57D11] font-semibold border-b-2">
                                            All @if ($notifications->where('is_archive', 0)->count() != 0)
                                                ({{ $notifications->where('is_archive', 0)->count() }})
                                            @endif
                                        </button>
                                        <button id="tab-unread" class="tab-btn px-4 py-2 text-gray-500">
                                            Unread @if ($notifications->where('is_read', 0)->where('is_archive', 0)->count() != 0)
                                                ({{ $notifications->where('is_read', 0)->where('is_archive', 0)->count() }})
                                            @endif
                                        </button>
                                        <button id="tab-archived" class="tab-btn px-4 py-2 text-gray-500">
                                            Archived @if ($notifications->where('is_archive', 1)->count() != 0)
                                                ({{ $notifications->where('is_archive', 1)->count() }})
                                            @endif
                                        </button>
                                    </div>

                                    <!-- All Notifications -->
                                    <section id="tab-content-all"
                                        class="divide-y divide-gray-100 w-full h-60 overflow-auto">
                                        @forelse ($notifications->where('is_archive', 0) as $notification)
                                            <div class="flex items-center justify-between gap-5 p-3 w-full cursor-pointer 
    hover:bg-gray-50 {{ $notification->is_read ? 'bg-white' : 'bg-gray-100' }}"
                                                onclick="openAllNotificationModal({{ $notification->id }}, '{{ addslashes($notification->message) }}', {{ $notification->is_read ? 'true' : 'false' }}, 'tab-all')">

                                                <div class="flex items-center gap-3 w-2/3">
                                                    <div class="w-auto h-auto">
                                                        <div
                                                            class="w-10 h-10 rounded-full border border-[#F57D11] overflow-hidden">
                                                            <x-image path="resources/img/default-male.png"
                                                                className="w-full h-full" />
                                                        </div>
                                                    </div>
                                                    <div class="w-full truncate">
                                                        <p class="text-sm font-semibold truncate">
                                                            {{ $notification->message }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 truncate">
                                                            {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="flex items-center space-x-2">
                                                    <div class="relative group">
                                                        <button
                                                            class="decline-btn px-2 py-1 bg-gray-400 hover:bg-black text-white rounded flex items-center justify-center gap-1"
                                                            onclick="event.stopPropagation(); archiveNotification({{ $notification->id }})">
                                                            <span
                                                                class="material-symbols--archive-rounded w-4 h-4"></span>
                                                            <span
                                                                class="text-black absolute -top-4 opacity-0 group-hover:opacity-100 animate-transition text-[11px] font-semibold ">
                                                                Archive
                                                            </span>
                                                        </button>
                                                    </div>
                                                    @if (!$notification->is_read)
                                                        <span class="bg-[#F57D11] w-2 h-2 rounded-full"></span>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <div
                                                class="w-full h-full flex justify-center items-center text-gray-600 text-sm">
                                                Nothing to see here.
                                            </div>
                                        @endforelse

                                        <div id="AllNotificationModal"
                                            class="w-full h-full fixed top-0 left-0 z-[100] flex items-center justify-center  overflow-x-hidden overflow-y-auto bg-black bg-opacity-70 hidden transition ease-in duration-500">
                                            <div
                                                class="lg:!w-1/3 md:w-1/2 w-full flex flex-col p-10 gap-5 bg-white rounded-2xl transition ease-in duration-500">
                                                <div class="flex w-full flex-col items-start gap-3 text-wrap">
                                                    <x-page-title title="Approval Request" titleClass="text-xl" />
                                                    <p id="allNotificationMessage"
                                                        class="text-gray-800 w-full text-wrap">
                                                    <p class="text-sm font-semibold text-gray-600">Requested DTR:
                                                        <span id="DateNotificationMessage"
                                                            class="text-[#F57D11] font-semibold text-base">date
                                                            here</span>
                                                    </p>
                                                    {{-- <p id="dateMessage" class="mt-2 text-gray-600 w-full text-wrap">
                                                    date here
                                                </p> --}}
                                                    <div class="flex gap-3 items-center justify-end w-full mt-2">
                                                        <x-button onClick="showNotificationModal()" label="View"
                                                            className="!px-8" tertiary button />
                                                        <x-button onClick="closeAllNotificationModal()" label="Close"
                                                            className="!px-8" primary button />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>

                                    <!-- Unread Notifications -->
                                    <section id="tab-content-unread"
                                        class="hidden divide-y divide-gray-100 w-full h-60 overflow-auto">
                                        @forelse ($notifications->where('is_read', 0)->where('is_archive', 0) as $notification)
                                            <div class="flex items-center justify-between gap-5 p-3 w-full cursor-pointer 
    hover:bg-gray-50 {{ $notification->is_read ? 'bg-white' : 'bg-gray-100' }}"
                                                onclick="openUnreadNotificationModal({{ $notification->id }}, '{{ addslashes($notification->message) }}', {{ $notification->is_read ? 'true' : 'false' }}, 'tab-unread')">
                                                <div class="flex items-center gap-3 w-2/3">
                                                    <div class="w-auto h-auto">
                                                        <div
                                                            class="w-10 h-10 rounded-full border border-[#F57D11] overflow-hidden">
                                                            <x-image path="resources/img/default-male.png"
                                                                className="h-full w-full" />
                                                        </div>
                                                    </div>
                                                    <div class="w-full truncate">
                                                        <p class="text-sm font-semibold truncate">
                                                            {{ $notification->message }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 truncate">
                                                            {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="flex items-center space-x-2">
                                                    <div class="relative group">
                                                        <button
                                                            class="decline-btn px-2 py-1 bg-gray-400 hover:bg-black text-white rounded flex items-center justify-center gap-1"
                                                            onclick="event.stopPropagation(); archiveNotification({{ $notification->id }})">
                                                            <span
                                                                class="material-symbols--archive-rounded w-4 h-4"></span>
                                                            <span
                                                                class="text-black absolute -top-4 opacity-0 group-hover:opacity-100 animate-transition text-[11px] font-semibold ">
                                                                Archive
                                                            </span>
                                                        </button>
                                                    </div>
                                                    @if (!$notification->is_read)
                                                        <span class="bg-[#F57D11] w-2 h-2 rounded-full"></span>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <div
                                                class="w-full h-full flex justify-center items-center text-gray-600 text-sm">
                                                Nothing to see here.
                                            </div>
                                        @endforelse

                                        <div id="UnreadNotificationModal"
                                            class="w-full h-full fixed top-0 left-0 z-[100] flex items-center justify-center  overflow-x-hidden overflow-y-auto bg-black bg-opacity-70 hidden transition ease-in duration-500">
                                            <div
                                                class="lg:!w-1/3 md:w-1/2 w-full flex flex-col p-10 gap-5 bg-white rounded-2xl transition ease-in duration-500">
                                                <div class="flex w-full flex-col items-start gap-3 text-wrap">
                                                    <x-page-title title="Approval Request" titleClass="text-xl" />
                                                    <p id="unreadNotificationMessage"
                                                        class="text-gray-800 w-full text-wrap">
                                                    <p class="text-sm font-semibold text-gray-600">Requested DTR:
                                                        <span id="UnreadDateNotificationMessage"
                                                            class="text-[#F57D11] font-semibold text-base">date
                                                            here</span>
                                                    </p>
                                                    {{-- <p id="dateMessage" class="mt-2 text-gray-600 w-full text-wrap">
                                                    date here
                                                </p> --}}
                                                    <div class="flex gap-3 items-center justify-end w-full mt-2">
                                                        <x-button onClick="showNotificationModal()" label="View"
                                                            className="!px-8" tertiary button />
                                                        <x-button onClick="closeUnreadNotificationModal()"
                                                            label="Close" className="!px-8" primary button />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>

                                    <!-- Archived Notifications -->
                                    <section id="tab-content-archived"
                                        class="hidden divide-y divide-gray-100 w-full h-60 overflow-auto">
                                        @forelse ($notifications->where('is_archive', 1) as $notification)
                                            <div class="flex items-center justify-between gap-5 p-3 w-full cursor-pointer 
    hover:bg-gray-50 {{ $notification->is_read ? 'bg-white' : 'bg-gray-100' }}"
                                                onclick="openArchiveNotificationModal({{ $notification->id }}, '{{ addslashes($notification->message) }}', {{ $notification->is_read ? 'true' : 'false' }}, 'tab-archive')">
                                                <div class="flex items-center gap-3 w-2/3">
                                                    <div class="h-auto w-auto">
                                                        <div
                                                            class="w-10 h-10 rounded-full border border-gray-400 overflow-hidden">
                                                            <x-image path="resources/img/default-male.png"
                                                                className="w-full h-full" />
                                                        </div>
                                                    </div>
                                                    <div class="w-full truncate">
                                                        <p class="text-sm font-semibold truncate">
                                                            {{ $notification->message }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 truncate">
                                                            {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div>
                                                    @if (!$notification->is_read)
                                                        <div class="bg-[#F57D11] w-2 h-2 rounded-full"></div>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <div
                                                class="w-full h-full flex justify-center items-center text-gray-600 text-sm">
                                                Nothing to see here.
                                            </div>
                                        @endforelse
                                    </section>

                                    <div id="ArchiveNotificationModal"
                                        class="w-full h-full fixed top-0 left-0 z-[100] flex items-center justify-center  overflow-x-hidden overflow-y-auto bg-black bg-opacity-70 hidden transition ease-in duration-500">
                                        <div
                                            class="lg:!w-1/3 md:w-1/2 w-full flex flex-col p-10 gap-5 bg-white rounded-2xl transition ease-in duration-500">
                                            <div class="flex w-full flex-col items-start gap-3 text-wrap">
                                                <x-page-title title="Approval Request" titleClass="text-xl" />
                                                <p id="archiveNotificationMessage"
                                                    class="text-gray-800 w-full text-wrap">
                                                <p class="text-sm font-semibold text-gray-600">Requested DTR: <span
                                                        id="ArchiveDateNotificationMessage"
                                                        class="text-[#F57D11] font-semibold text-base">date
                                                        here</span></p>
                                                {{-- <p id="dateMessage" class="mt-2 text-gray-600 w-full text-wrap">
                                                    date here
                                                </p> --}}
                                                <div class="flex gap-3 items-center justify-end w-full mt-2">
                                                    <x-button onClick="showNotificationModal()" label="View"
                                                        className="!px-8" tertiary button />
                                                    <x-button onClick="closeArchiveNotificationModal()" label="Close"
                                                        className="!px-8" primary button />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="dropdown relative inline-flex">
                                <button type="button" id="dropdown-profile" data-target="dropdown-show-profile"
                                    class="dropdown-profile items-center gap-2 hover:bg-gray-100 rounded-lg py-2 px-3 overflow-hidden inline-flex">
                                    <div class="w-auto h-auto">
                                        <div
                                            class="w-10 h-10 rounded-full shadow border border-[#F53C11] overflow-hidden">
                                            <x-image
                                                path="{{ optional(\App\Models\File::find(optional(\App\Models\Profile::find(Auth::user()->profile_id))->file_id))->path ?? 'resources/img/default-male.png' }}"
                                                className="w-full h-full" />
                                        </div>
                                    </div>
                                    <p class="lg:block hidden capitalize">{{ Auth::user()->firstname }}</p>
                                    <span class="iconamoon--arrow-down-2"></span>
                                </button>
                                <div id="dropdown-show-profile"
                                    class="dropdown-menu-profile hidden rounded-lg shadow-lg border border-gray-300 bg-white absolute top-full right-0 w-72 divide-y divide-gray-200">
                                    <ul class="py-2">
                                        <li>
                                            <a class="block px-6 py-2 hover:bg-gray-100 text-gray-900 font-semibold"
                                                href="{{ route('admin.profile') }}"> Profile </a>
                                        </li>
                                    </ul>
                                    <div class="pt-2">
                                        <x-form.container routeName="logout" method="POST" className="w-full">
                                            <x-button label="Logout"
                                                className="px-6 py-2 hover:bg-gray-100 text-[#F53C11] font-semibold w-full text-start"
                                                submit />
                                        </x-form.container>
                                    </div>
                                </div>
                            </div>

                        </section>
                    </div>
                </section>

                <section class="lg:!p-10 p-5">
                    {{ $slot }}
                </section>
            </main>
        </div>

        <script>
            const menuToggle = document.getElementById("menu-toggle");
            const mobileMenu = document.getElementById("mobile-menu");

            menuToggle.addEventListener("click", () => {
                mobileMenu.classList.toggle("-translate-x-full");
            });

            // notifications and profile
            document.addEventListener("DOMContentLoaded", function() {
                const dropdownProfile = document.getElementById("dropdown-profile");
                const dropdownMenuProfile = document.getElementById("dropdown-show-profile");

                const dropdownNotification = document.getElementById("dropdown-notification");
                const dropdownMenuNotification = document.getElementById("dropdown-show-notification");

                const closeDropdown = document.getElementById("close-dropdown");

                const tabs = {
                    all: document.getElementById("tab-all"),
                    unread: document.getElementById("tab-unread"),
                    archived: document.getElementById("tab-archived")
                };

                const tabContents = {
                    all: document.getElementById("tab-content-all"),
                    unread: document.getElementById("tab-content-unread"),
                    archived: document.getElementById("tab-content-archived")
                };

                function closeAllDropdowns() {
                    dropdownMenuProfile?.classList.add("hidden");
                    dropdownMenuNotification?.classList.add("hidden");
                }

                function toggleDropdown(dropdownButton, dropdownMenu, otherDropdownMenu) {
                    if (dropdownMenu.classList.contains("hidden")) {
                        closeAllDropdowns(); // Close any open dropdown first
                        dropdownMenu.classList.remove("hidden"); // Open clicked dropdown
                    } else {
                        dropdownMenu.classList.add("hidden"); // Close dropdown if already open
                    }
                }

                // Toggle profile dropdown
                dropdownProfile?.addEventListener("click", function(event) {
                    event.stopPropagation();
                    toggleDropdown(dropdownProfile, dropdownMenuProfile, dropdownMenuNotification);
                });

                // Toggle notification dropdown
                dropdownNotification?.addEventListener("click", function(event) {
                    event.stopPropagation();
                    toggleDropdown(dropdownNotification, dropdownMenuNotification, dropdownMenuProfile);
                });

                closeDropdown?.addEventListener("click", function() {
                    closeAllDropdowns();
                });

                // Close dropdown when clicking outside
                document.addEventListener("click", function(event) {
                    if (
                        !dropdownProfile?.contains(event.target) &&
                        !dropdownMenuProfile?.contains(event.target) &&
                        !dropdownNotification?.contains(event.target) &&
                        !dropdownMenuNotification?.contains(event.target)
                    ) {
                        closeAllDropdowns();
                    }
                });

                // Tab switching functionality
                function switchTab(activeTab, activeContent) {
                    // Reset all tabs
                    Object.values(tabs).forEach(tab => {
                        tab.classList.remove("text-[#F57D11]", "border-[#F57D11]", "font-semibold",
                            "border-b-2");
                        tab.classList.add("text-gray-500");
                    });

                    Object.values(tabContents).forEach(content => content.classList.add("hidden"));

                    // Activate selected tab
                    activeTab.classList.add("text-[#F57D11]", "border-[#F57D11]", "font-semibold",
                        "border-b-2");
                    activeTab.classList.remove("text-gray-500");

                    activeContent.classList.remove("hidden");
                }

                // Set initial active tab to 'All'
                switchTab(tabs.all, tabContents.all);

                // Add event listeners for tab clicks
                Object.keys(tabs).forEach(key => {
                    tabs[key].addEventListener("click", function() {
                        switchTab(tabs[key], tabContents[key]);
                    });
                });
            });
        </script>
    @else
        {{-- login / register form layout --}}
        <main class="h-full w-full bg-white">
            {{ $slot }}
        </main>
    @endif
</body>

</html>
<script>
    let notification_id = 0;

    function openAllNotificationModal(notificationId, message, isRead, tab) {

        const modal = document.getElementById("AllNotificationModal");
        const messageElement = document.getElementById("allNotificationMessage");
        const dateElement = document.getElementById("DateNotificationMessage");

        if (!modal || !messageElement || !dateElement) {
            console.error("Modal elements not found!");
            return;
        }

        let text = message;

        let msgText;
        let dateText;

        // Find the last occurrence of "DTR." and extract the message
        let lastIndex = text.lastIndexOf("DTR.");

        if (lastIndex !== -1) {
            msgText = text.substring(0, lastIndex + 4); // Extracts from first word to "DTR."
            dateText = text.substring(lastIndex + 5).trim(); // Extracts everything after "DTR."
        }

        messageElement.innerText = msgText;
        dateElement.innerText = dateText;


        // Show modal
        modal.classList.remove("hidden");

        if (!isRead) {
            markAsRead(notificationId);
        }
    }

    function openUnreadNotificationModal(notificationId, message, isRead, tab) {

        const modal = document.getElementById("UnreadNotificationModal");
        const messageElement = document.getElementById("unreadNotificationMessage");
        const dateElement = document.getElementById("UnreadDateNotificationMessage");

        if (!modal || !messageElement || !dateElement) {
            console.error("Modal elements not found!");
            return;
        }

        let text = message;

        let msgText;
        let dateText;

        // Find the last occurrence of "DTR." and extract the message
        let lastIndex = text.lastIndexOf("DTR.");


        if (lastIndex !== -1) {
            msgText = text.substring(0, lastIndex + 4); // Extracts from first word to "DTR."
            dateText = text.substring(lastIndex + 5).trim(); // Extracts everything after "DTR."
        }

        messageElement.innerText = msgText;
        dateElement.innerText = dateText;

        // Show modal
        modal.classList.remove("hidden");

        if (!isRead) {
            markAsRead(notificationId);
        }

    }

    function openArchiveNotificationModal(notificationId, message, isRead, tab) {

        const modal = document.getElementById("ArchiveNotificationModal");
        const messageElement = document.getElementById("archiveNotificationMessage");
        const dateElement = document.getElementById("ArchiveDateNotificationMessage");

        if (!modal || !messageElement || !dateElement) {
            console.error("Modal elements not found!");
            return;
        }

        let text = message;

        let msgText;
        let dateText;

        // Find the last occurrence of "DTR." and extract the message
        let lastIndex = text.lastIndexOf("DTR.");

        if (lastIndex !== -1) {
            msgText = text.substring(0, lastIndex + 4); // Extracts from first word to "DTR."
            dateText = text.substring(lastIndex + 5).trim(); // Extracts everything after "DTR."
        }

        messageElement.innerText = msgText;
        dateElement.innerText = dateText;

        // Show modal
        modal.classList.remove("hidden");

        if (!isRead) {
            markAsRead(notificationId);
        }

    }

    function closeAllNotificationModal() {
        const modal = document.getElementById("AllNotificationModal");
        if (modal) {
            modal.classList.add("hidden");
        }
    }

    function closeUnreadNotificationModal() {
        const modal = document.getElementById("UnreadNotificationModal");
        if (modal) {
            modal.classList.add("hidden");
        }
    }

    function closeArchiveNotificationModal() {
        const modal = document.getElementById("ArchiveNotificationModal");
        if (modal) {
            modal.classList.add("hidden");
        }
    }



    function showNotificationModal() {
        window.location.href = '/admin/approvals';
    }


    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

    function markAsRead(notificationId) {
        app_url = `{{ url('/notifications/${notificationId}/mark-as-read') }}`;
        fetch(app_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({})
            })
            .then(response => {

                if (response.status === 200) {
                    const notificationCount = document.getElementById('notification-count');
                    if (notificationCount) {
                        notificationCount.innerText = Math.max(0, parseInt(notificationCount.innerText) - 1);
                    }
                }
            }).catch(error => console.error('Error:', error));
    }

    let app_url = '';

    function archiveNotification(notificationId) {

        app_url = `{{ url('/notifications/${notificationId}/archive') }}`;

        fetch(app_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({})
            })
            .then(response => {

                if (response.status === 200) {
                    location.reload();
                }
            }).catch(error => console.error('Error:', error));
    }

    // Ensure modals work in each tab
    function attachClickHandlers() {
        document.querySelectorAll(".notification-item").forEach(item => {
            item.addEventListener("click", function() {
                const id = this.getAttribute("data-id");
                const message = this.getAttribute("data-message");
                const isRead = this.getAttribute("data-is-read") === "true";
                const tab = this.getAttribute("data-tab");
                openNotificationModal(id, message, isRead, tab);
            });
        });
    }

    document.addEventListener("DOMContentLoaded", attachClickHandlers);
</script>
