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
                        <x-admin.sidebar-menu routeName="users.dashboard" label="Dashboard"
                            icon="akar-icons--dashboard">
                            <span class="akar-icons--dashboard"></span>
                            <p>Dashboard</p>
                        </x-admin.sidebar-menu>
                        <x-admin.sidebar-menu routeName="users.dtr" label="DTR" icon="mingcute--paper-line">
                            <span class="mingcute--paper-line"></span>
                            <p>DTR</p>
                        </x-admin.sidebar-menu>
                        <x-admin.sidebar-menu routeName="users.request" label="Request" icon="ph--hand-deposit">
                            <span class="ph--hand-deposit"></span>
                            <p>Request</p>
                        </x-admin.sidebar-menu>
                        <x-admin.sidebar-menu routeName="users.settings" label="Settings" icon="solar--settings-linear">
                            <span class="solar--settings-linear"></span>
                            <p>Settings</p>
                        </x-admin.sidebar-menu>
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

                    <div class="px-5 pt-5 w-full flex justify-center">
                        <x-logo />
                    </div>

                    <section class="w-full flex flex-col gap-5 justify-center items-center px-5">
                        <section class="flex flex-col gap-2 items-center justify-center">
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
                        <button type="button" onclick="openLogoutModal()"
                            class="bg-[#f56d11] text-white p-1 rounded hover:scale-110 hover:shadow-md transition inline-flex items-center gap-2 px-4 py-1 justify-center">
                            <span class="ant-design--poweroff-outlined w-4 h-4"></span>
                            <p class="text-sm">Logout</p>
                        </button>
                    </section>

                    <section class="w-full border-t border-gray-100 py-5 px-5">
                        <x-users.sidebar-menu routeName="users.dashboard" label="Dashboard"
                            icon="akar-icons--dashboard" />
                        <x-users.sidebar-menu routeName="users.dtr" label="DTR" icon="mingcute--paper-line" />
                        <x-users.sidebar-menu routeName="users.request" label="Request" icon="ph--hand-deposit" />
                        <x-users.sidebar-menu routeName="users.settings" label="Settings"
                            icon="solar--settings-linear" />
                    </section>
                </nav>
            </aside>

            <!-- Main Content (Auto Scroll) -->
            <main
                class="col-span-9 overflow-auto w-full lg:!h-[calc(100vh)] h-[calc(100vh-4rem)] bg-gray-100 lg:!p-10 p-5">
                {{ $slot }}
            </main>

            <!-- Logout Modal -->
            <div id="logoutModal"
                class="fixed inset-0 z-50 h-screen flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
                <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                    <h2 class="text-xl font-bold text-black">Confirm Logout</h2>
                    <p class="text-gray-600 mt-2">Are you sure you want to log out?</p>

                    <div class="flex justify-end mt-7 space-x-3">
                        <span>
                            <button onclick="closeLogoutModal()"
                                class="px-5 py-2 text-[#f56d11] rounded border border-gray-300 hover:border-[#f56d11]">
                                Cancel
                            </button>
                        </span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="px-5 py-2 bg-red-500 text-white rounded hover:bg-red-600 font-semibold">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const menuToggle = document.getElementById("intern-menu-toggle");
            const mobileMenu = document.getElementById("mobile-menu");

            // Open and Close Logout Modal
            function openLogoutModal() {
                document.getElementById('logoutModal').classList.remove('hidden');
            }

            function closeLogoutModal() {
                document.getElementById('logoutModal').classList.add('hidden');
            }

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
            <aside class="col-span-3 h-[calc(100vh)] sticky top-0 w-full p-5 shadow-xl bg-white">
                <div class="flex items-center gap-5 justify-start px-3 pt-5">
                    <button id="toggleMenu" class="hover:scale-105 transition">
                        <span class="mynaui--menu w-7 h-7"></span>
                    </button>
                    <img id="sidebar-logo" src="{{ asset('image/rweb_logo.png') }}" class="w-[200px] h-auto">
                </div>
                <div class="my-7 bg-[#f56d11] h-[0.1px] rounded-full w-full"></div>

                {{-- admin front-end navbar --}}
                @if (Request::routeIs('admin.front-end*'))
                    <x-admin.sidebar-menu icon="ic--baseline-space-dashboard" label="Dashboard"
                        routeName="admin.front-end.dashboard" />
                    <x-admin.sidebar-menu icon="mdi--file-cog" label="Project Development"
                        routeName="admin.front-end.project-development" />
                    <x-admin.sidebar-menu icon="fluent--clipboard-text-edit-48-filled" label="Revision Checklist"
                        routeName="admin.front-end.revision-checklist" />
                    <x-admin.sidebar-menu icon="ic--baseline-discount" label="Promotions"
                        routeName="admin.front-end.promotions" />
                    <x-admin.sidebar-menu icon="streamline--manual-book-solid" label="Instruction Manual"
                        routeName="admin.front-end.instructions-manual" />
                    <x-admin.sidebar-menu icon="fa--user" label="Profile" routeName="admin.front-end.profile" />

                    {{-- admin dtr navbar --}}
                @elseif (Request::routeIs('admin.dtr*'))
                    <x-admin.sidebar-menu icon="ic--baseline-space-dashboard" label="Dashboard"
                        routeName="admin.dtr.dashboard" />
                    <x-admin.sidebar-menu icon="fluent--clipboard-text-edit-48-filled" label="Approvals"
                        routeName="admin.dtr.approvals" />
                    <x-admin.sidebar-menu icon="fa--users" label="Interns" routeName="admin.dtr.interns" />
                    <x-admin.sidebar-menu icon="mdi--clipboard-text-history" label="History"
                        routeName="admin.dtr.history" />
                    <x-admin.sidebar-menu icon="ic--round-school" label="Schools" routeName="admin.dtr.schools" />
                    <x-admin.sidebar-menu icon="fa--user" label="Profile" routeName="admin.dtr.profile" />
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

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const toggleMenu = document.getElementById("toggleMenu");
                const aside = document.querySelector("aside");
                const section = document.querySelector("section");
                const sidebarLabels = document.querySelectorAll(".sidebar-label");
                const sidebarIcons = document.querySelectorAll(".sidebar-icon");
                const sidebarTooltips = document.querySelectorAll(".tooltip");
                const logo = document.querySelector("#sidebar-logo");

                if (localStorage.getItem("sidebarCollapsed") === "true") {
                    collapseSidebar();
                }

                toggleMenu.addEventListener("click", function() {
                    if (aside.classList.contains("col-span-3")) {
                        collapseSidebar();
                        localStorage.setItem("sidebarCollapsed", "true");
                    } else {
                        expandSidebar();
                        localStorage.setItem("sidebarCollapsed", "false");
                    }
                });

                function collapseSidebar() {
                    aside.classList.remove("col-span-3", "p-5");
                    aside.classList.add("col-span-1", "p-2");

                    section.classList.remove("col-span-9");
                    section.classList.add("col-span-11");

                    logo.classList.add("hidden");

                    sidebarLabels.forEach(label => {
                        label.classList.add("hidden");
                    });

                    sidebarIcons.forEach(icon => {
                        icon.classList.add("mx-auto");
                    });

                    sidebarTooltips.forEach(tooltip => {
                        tooltip.classList.remove("hidden");
                    });

                    aside.classList.add("flex", "flex-col", "items-center");
                }

                function expandSidebar() {
                    aside.classList.add("col-span-3", "p-5");
                    aside.classList.remove("col-span-1", "p-2");

                    section.classList.add("col-span-9");
                    section.classList.remove("col-span-11");

                    logo.classList.remove("hidden");

                    sidebarLabels.forEach(label => {
                        label.classList.remove("hidden");
                    });

                    sidebarIcons.forEach(icon => {
                        icon.classList.remove("mx-auto");
                    });

                    sidebarTooltips.forEach(tooltip => {
                        tooltip.classList.add("hidden");
                    });

                    aside.classList.remove("items-center");
                }
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
