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
                    <x-admin.sidebar-menu icon="akar-icons--dashboard" label="Dashboard" routeName="admin.dtr.dashboard" />
                    {{-- <x-admin.sidebar-menu route="admin.approvals">
                        <div class="w-auto h-auto flex items-center"><span class="lucide--check-check"></span>
                        </div>
                        <p>Approvals</p>
                    </x-sidebar-menu>
                    <x-admin.sidebar-menu route="admin.users">
                        <div class="w-auto h-auto flex items-center"><span class="cuida--users-outline"></span>
                        </div>
                        <p>Users</p>
                    </x-sidebar-menu>
                    <x-admin.sidebar-menu route="admin.histories">
                        <div class="w-auto h-auto flex items-center"><span
                                class="material-symbols--history-rounded w-6 h-6"></span></div>
                        <p>History</p>
                    </x-sidebar-menu>
                    <x-admin.sidebar-menu route="admin.schools">
                        <div class="w-auto h-auto flex items-center"><span
                                class="material-symbols--school-outline-rounded !w-6 !h-6"></span></div>
                        <p>Schools</p>
                    </x-sidebar-menu>
                    <x-admin.sidebar-menu route="admin.profile">
                        <div class="w-auto h-auto flex items-center"><span class="cuida--user-outline"></span>
                        </div>
                        <p>Profile</p>
                    </x-sidebar-menu> --}}
                </nav>
            </aside>

            <!-- Left Sidebar (Sticky on Large Screens) -->
            <aside class="hidden lg:block col-span-2 bg-white shadow-xl sticky top-0 h-[calc(100vh)] overflow-auto py-5">
                <div class="px-5 w-full">
                    <x-logo />
                </div>
                <!-- Navigation -->
                <nav class="mt-10">
                    <x-admin.sidebar-menu icon="akar-icons--dashboard" label="Dashboard"
                        routeName="admin.dtr.dashboard" />
                    {{-- <x-admin.sidebar-menu route="admin.approvals">
                        <div class="w-auto h-auto flex items-center"><span class="lucide--check-check"></span>
                        </div>
                        <p>Approvals</p>
                    </x-sidebar-menu>
                    <x-admin.sidebar-menu route="admin.users">
                        <div class="w-auto h-auto flex items-center"><span class="cuida--users-outline"></span>
                        </div>
                        <p>Users</p>
                    </x-sidebar-menu>
                    <x-admin.sidebar-menu route="admin.histories">
                        <div class="w-auto h-auto flex items-center"><span
                                class="material-symbols--history-rounded w-6 h-6"></span></div>
                        <p>History</p>
                    </x-sidebar-menu>
                    <x-admin.sidebar-menu route="admin.schools">
                        <div class="w-auto h-auto flex items-center"><span
                                class="material-symbols--school-outline-rounded !w-6 !h-6"></span></div>
                        <p>Schools</p>
                    </x-sidebar-menu>
                    <x-admin.sidebar-menu route="admin.profile">
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
