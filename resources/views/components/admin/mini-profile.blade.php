<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<div
    class="flex items-center md:gap-5 gap-3 justify-end bg-[#f56d11] text-white lg:pl-24 lg:pr-10 px-10 lg:py-6 py-4 lg:rounded-bl-[50px] lg:w-fit w-full shadow-lg">
    <div class="col-span-1 flex items-center justify-start w-full">
        <button id="admin-menu-toggle" class="text-2xl lg:hidden w-fit h-fit">
            ☰
        </button>
    </div>
    <span class="sm:block hidden ">
        <div class="flex flex-col items-end justify-end">
            <p class="text-nowrap font-semibold">Hi, <span class="capitalize">{{ Auth::user()->firstname }}</span>!</p>
            <p class="text-gray-200 text-sm font-medium text-nowrap">
                {{ ucwords(str_replace('_', ' ', Auth::user()->roles->position)) }}
            </p>

        </div>
    </span>

    {{-- notification --}}
    <section class="flex items-center gap-2">
        <div class="dropdown relative inline-flex self-center">
            <button type="button" id="dropdown-notification"
                class="dropdown-notification w-10 h-10 relative text-orange-500 bg-white p-2 rounded-full hover:bg-gray-100 cursor-pointer">
                <span class="mi--notification w-full h-full relative"></span>
                @if ($notifications->where('is_read', 0)->count())
                    {{-- <div
                        class="absolute top-0 right-0 w-5 h-5 rounded-full bg-[#F53C11] p-1 text-center flex items-center justify-center text-white">
                        <span class="text-[10px] font-semibold m-auto">{{ $notifications->where('is_read', 0)->count()
                            }}</span>
                    </div> --}}
                    <div class="absolute -top-2 -right-2">
                        <div
                            class=" w-6 h-6 rounded-full bg-[#F53C11] border border-white p-1 text-center flex items-center justify-center text-white">
                            <p class="text-[10px] font-semibold">
                                @if ($notifications->where('is_read', 0)->count() <= 99)
                                    {{ $notifications->where('is_read', 0)->count() }}
                                @else
                                    99+
                                @endif
                            </p>
                        </div>
                    </div>
                @endif
            </button>

            {{-- kent --}}
            <div id="dropdown-show-notification"
                class="dropdown-menu-notification hidden rounded-lg shadow-lg border border-gray-300 bg-white absolute top-full -right-20 mt-2 md:w-[600px] sm:w-[400px] w-[300px] z-20">

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

                <!-- All Notifications with Infinite Scroll -->
                <section id="tab-content-all"
                    class="divide-y divide-gray-100 w-full h-60 overflow-auto overflow-x-hidden"
                    onscroll="allTabLoadMoreNotifications()">
                    @forelse ($notifications->where('is_archive', 0) as $notification)
                        <div class="flex items-center justify-between gap-5 p-3 w-full cursor-pointer hover:bg-gray-50 {{ $notification->is_read ? 'bg-white' : 'bg-gray-100' }}"
                            onclick="openNotificationModal({{ $notification->id }}, '{{ addslashes($notification->message) }}', {{ $notification->is_read ? 'true' : 'false' }}, 'tab-all', '{{ addslashes($notification->type) }}', '{{ addslashes($notification->title) }}')">
                            <div class="flex items-center gap-3 w-2/3">
                                <div class="w-auto h-auto">
                                    <div class="w-10 h-10 rounded-full border border-[#F57D11] overflow-hidden">
                                        <img id="all-notification-image-profile"
                                            src="{{ $notification->file_path ? $notification->file_path : asset('resources/img/default-male.png') }}"
                                            class="w-full h-full" />
                                    </div>
                                </div>
                                <div class="w-full truncate">
                                    <p class="text-sm text-red-500 font-semibold truncate">{{ $notification->title }}
                                    </p>
                                    <p class="text-sm text-gray-500 font-semibold truncate">
                                        {{ $notification->message }}</p>
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
                                        <span class="material-symbols--archive-rounded w-4 h-4"></span>
                                        <span
                                            class="text-black absolute -top-4 opacity-0 group-hover:opacity-100 animate-transition text-[11px] font-semibold">Archive</span>
                                    </button>
                                </div>
                                @if (!$notification->is_read)
                                    <span class="bg-[#F57D11] w-2 h-2 rounded-full"></span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="w-full h-full flex justify-center items-center text-gray-600 text-sm">Nothing to see
                            here.</div>
                    @endforelse
                    <div id="loading-indicator" style="display: none; text-align: center;">Loading Here...</div>
                </section>

                <!-- Unread Notifications -->
                <section id="tab-content-unread"
                    class="divide-y divide-gray-100 w-full h-60 overflow-auto overflow-x-hidden"
                    onscroll="unreadTabLoadMoreNotifications()">
                    @forelse ($notifications->where('is_read', 0)->where('is_archive', 0) as $notification)
                        <div class="flex items-center justify-between gap-5 p-3 w-full cursor-pointer 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    hover:bg-gray-50 {{ $notification->is_read ? 'bg-white' : 'bg-gray-100' }}"
                            onclick="openNotificationModal({{ $notification->id }}, '{{ addslashes($notification->message) }}', {{ $notification->is_read ? 'true' : 'false' }}, 'tab-unread', '{{ addslashes($notification->type) }}', '{{ addslashes($notification->title) }}')">
                            <div class="flex items-center gap-3 w-2/3">
                                <div class="w-auto h-auto">
                                    <div class="w-10 h-10 rounded-full border border-[#F57D11] overflow-hidden">
                                        <x-image path="resources/img/default-male.png" className="h-full w-full" />
                                    </div>
                                </div>
                                <div class="w-full truncate">
                                    <p class="text-sm text-red-500 font-semibold truncate">
                                        {{ $notification->title }}
                                    </p>
                                    <p class="text-sm text-gray-500 font-semibold truncate">
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
                                        <span class="material-symbols--archive-rounded w-4 h-4"></span>
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
                        <div class="w-full h-full flex justify-center items-center text-gray-600 text-sm">
                            Nothing to see here.
                        </div>
                    @endforelse
                </section>

                <!-- Archived Notifications -->
                <section id="tab-content-archived"
                    class="divide-y divide-gray-100 w-full h-60 overflow-auto overflow-x-hidden"
                    onscroll="archivedTabLoadMoreNotifications()">
                    @forelse ($notifications->where('is_archive', 1) as $notification)
                        <div class="flex items-center justify-between gap-5 p-3 w-full cursor-pointer 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                hover:bg-gray-50 {{ $notification->is_read ? 'bg-white' : 'bg-gray-100' }}"
                            onclick="openNotificationModal({{ $notification->id }}, '{{ addslashes($notification->message) }}', {{ $notification->is_read ? 'true' : 'false' }}, 'tab-archive', '{{ addslashes($notification->type) }}', '{{ addslashes($notification->title) }}')">
                            <div class="flex items-center gap-3 w-2/3">
                                <div class="h-auto w-auto">
                                    <div class="w-10 h-10 rounded-full border border-gray-400 overflow-hidden">
                                        <x-image path="resources/img/default-male.png" className="w-full h-full" />
                                    </div>
                                </div>
                                <div class="w-full truncate">
                                    <p class="text-sm text-red-500 font-semibold truncate">
                                        {{ $notification->title }}
                                    </p>
                                    <p class="text-sm text-gray-500 font-semibold truncate">
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
                        <div class="w-full h-full flex justify-center items-center text-gray-600 text-sm">
                            Nothing to see here.
                        </div>
                    @endforelse
                </section>
            </div>

        </div>
    </section>

    {{-- <h1 class="absolute top-0 z-10 px-3 py-1 rounded bg-[#f56d11] text-white text-sm -left-12">DTR</h1> --}}
    <!-- Profile Dropdown -->
    @php
        $admin_roles = ['admin', 'top_manager', 'supervisor', 'assistant_supervisor'];
    @endphp
    <div class="dropdown relative inline-flex hover:scale-105 transition">
        <span class="group">
            <button type="button" id="dropdown-profile" data-target="dropdown-show-profile"
                class="dropdown-profile inline-flex w-16 h-16 overflow-hidden rounded-full border-4 border-transparent group-hover:border-[#fdb783]/50"
                onclick="toggleDropdown()">
                <img draggable="false"
                    src="{{ \App\Models\File::where(
                        'id',
                        \App\Models\Profile::where('id', Auth::user()->profile_id)->first()->file_id,
                    )->first()->path .
                        '?=s100?t=' .
                        time() }}"
                    alt="user profile" class="w-full h-full border-2 border-white object-cover bg-white rounded-full">
            </button>
        </span>

        <!-- Dropdown Menu -->
        <div id="dropdown-show-profile"
            class="dropdown-menu-profile hidden rounded-lg overflow-hidden shadow-lg border border-gray-300 bg-white absolute top-full right-0 lg:w-72 w-40 divide-y divide-gray-200 text-black">
            @php
                $menuItems = [
                    'admin.smm*' => ['label' => 'SMM', 'route' => 'admin.smm.dashboard'],
                    'admin.dtr*' => ['label' => 'DTR', 'route' => 'admin.dtr.dashboard'],
                    'admin.web*' => ['label' => 'WEB DEVELOPMENT', 'route' => 'admin.web.dashboard'],
                ];
            @endphp


            @if (in_array(Auth::user()->roles->position, $admin_roles))
                <ul class="py-2">
                    @foreach ($menuItems as $route => $item)
                        <li>
                            <a href="{{ route($item['route']) }}" @class([
                                'block px-6 py-2 font-semibold cursor-pointer lg:text-base text-sm',
                                'bg-[#f56d11] text-white' => Request::routeIs($route),
                                'hover:bg-gray-100 text-gray-900' => !Request::routeIs($route),
                            ])>
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>

            @endif

            {{-- kent --}}
            <button onclick="openLogoutModal()"
                class="lg:text-base cursor-pointer w-full font-semibold text-sm text-red-500 flex gap-2 items-center justify-start px-6 py-3 hover:bg-red-500 hover:text-white">
                <span class="ant-design--poweroff-outlined w-6 h-6"></span>
                Logout
            </button>

        </div>
    </div>
    {{-- <img draggable="false" src="{{ \App\Models\File::where(
                'id',
                \App\Models\Profile::where('id', Auth::user()->profile_id)->first()->file_id,
            )->first()->path .
                '?=s100?t=' .
                time() }}" alt="user profile"
        class="w-16 h-16 object-cover bg-white rounded-full border-2 border-white"> --}}


    <!-- Logout Button -->
    {{-- <button type="button" onclick="openLogoutModal()"
        class="text-[#f56d11] bg-white p-1 rounded hover:scale-110 hover:shadow-md transition inline-flex">
        <span class="ant-design--poweroff-outlined w-6 h-6"></span>
    </button> --}}

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

    <div id="AllNotificationModals"
        class="w-full h-full fixed top-0 left-0 z-[100] flex items-center justify-center  overflow-x-hidden overflow-y-auto bg-black bg-opacity-70 hidden transition ease-in duration-500">
        <div
            class="lg:!w-1/3 md:w-1/2 w-full flex flex-col p-10 gap-5 bg-white rounded-2xl transition ease-in duration-500">
            <div class="flex w-full flex-col items-start gap-3 text-wrap">
                <div class="flex items-center gap-2 select-none">
                    <x-image path="resources/img/vector_icon.png" className="w-auto h-5" />
                    <h1 id="pageTitle"
                        class="lg:!text-xl sm:!text-base text-sm font-semibold text-[#F53C11] uppercase">
                        No Title
                    </h1>
                </div>
                <p id="allNotificationMessage" class="text-gray-800 w-full text-wrap">
                <p id="ContainerDateNotificationMessage" class="text-sm font-semibold text-gray-600">
                    Requested DTR:
                    <span id="DateNotificationMessage" class="text-[#F57D11] font-semibold text-base">date
                        here</span>
                </p>
                </p>
                {{-- <p id="dateMessage" class="mt-2 text-gray-600 w-full text-wrap">
                    date here
                </p> --}}
                <div class="flex gap-3 items-center justify-end w-full mt-2">
                    <x-button onClick="showNotificationModal()" label="View" className="!px-8" tertiary button />
                    <x-button onClick="closeNotificationModal('AllNotificationModals')" label="Close"
                        className="!px-8" primary button />
                </div>
            </div>
        </div>
    </div>

    <div id="UnreadNotificationModal"
        class="w-full h-full fixed top-0 left-0 z-[100] flex items-center justify-center overflow-x-hidden overflow-y-auto bg-black bg-opacity-70 hidden transition ease-in duration-500">
        <div
            class="lg:!w-1/3 md:w-1/2 w-full flex flex-col p-10 gap-5 bg-white rounded-2xl transition ease-in duration-500">
            <div class="flex w-full flex-col items-start gap-3 text-wrap">
                <div class="flex items-center gap-2 select-none">
                    <x-image path="resources/img/vector_icon.png" className="w-auto h-5" />
                    <h1 id="unReadPageTitle"
                        class="lg:!text-xl sm:!text-base text-sm font-semibold text-[#F53C11] uppercase">
                        No Title
                    </h1>
                </div>
                <p id="unreadNotificationMessage" class="text-gray-800 w-full text-wrap">
                <p id="UnreadContainerDateNotificationMessage" class="text-sm font-semibold text-gray-600">
                    Requested DTR:
                    <span id="UnreadDateNotificationMessage" class="text-[#F57D11] font-semibold text-base">date
                        here</span>
                </p>
                {{-- <p id="dateMessage" class="mt-2 text-gray-600 w-full text-wrap">
                    date here
                </p> --}}
                <div class="flex gap-3 items-center justify-end w-full mt-2">
                    <x-button onClick="showNotificationModal()" label="View" className="!px-8" tertiary button />
                    <x-button onClick="closeNotificationModal('UnreadNotificationModal')" label="Close"
                        className="!px-8" primary button />
                </div>
            </div>
        </div>
    </div>

    <div id="ArchiveNotificationModal"
        class="w-full h-full fixed top-0 left-0 z-[100] flex items-center justify-center  overflow-x-hidden overflow-y-auto bg-black bg-opacity-70 hidden transition ease-in duration-500">
        <div
            class="lg:!w-1/3 md:w-1/2 w-full flex flex-col p-10 gap-5 bg-white rounded-2xl transition ease-in duration-500">
            <div class="flex w-full flex-col items-start gap-3 text-wrap">
                <div class="flex items-center gap-2 select-none">
                    <x-image path="resources/img/vector_icon.png" className="w-auto h-5" />
                    <h1 id="archivePageTitle"
                        class="lg:!text-xl sm:!text-base text-sm font-semibold text-[#F53C11] uppercase">
                        No Title
                    </h1>
                </div>
                <p id="archiveNotificationMessage" class="text-gray-800 w-full text-wrap">
                <p id="ArchiveContainerDateNotificationMessage" class="text-sm font-semibold text-gray-600">
                    Requested DTR:
                    <span id="ArchiveDateNotificationMessage" class="text-[#F57D11] font-semibold text-base">date
                        here</span>
                </p>
                {{-- <p id="dateMessage" class="mt-2 text-gray-600 w-full text-wrap">
                    date here
                </p> --}}
                <div class="flex gap-3 items-center justify-end w-full mt-2">
                    <x-button onClick="showNotificationModal()" label="View" className="!px-8" tertiary button />
                    <x-button onClick="closeNotificationModal('ArchiveNotificationModal')" label="Close"
                        className="!px-8" primary button />
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    window.notificationsData = @json($notifications);
    window.userIdData = @json($userId);
</script>
<script src="{{ asset('js/profileModalDropDown.js') }}" defer></script>
<script src="{{ asset('js/notifications.js') }}" defer></script>
<script src="{{ asset('js/notificationScroll.js') }}" defer></script>
<script src="{{ asset('js/notificationClickEvent.js') }}" defer></script>
