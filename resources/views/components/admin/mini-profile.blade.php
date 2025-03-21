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
                <section id="tab-content-all" class="divide-y divide-gray-100 w-full h-60 overflow-auto">
                    @forelse ($notifications->where('is_archive', 0) as $notification)
                        <div class="flex items-center justify-between gap-5 p-3 w-full cursor-pointer 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    hover:bg-gray-50 {{ $notification->is_read ? 'bg-white' : 'bg-gray-100' }}"
                            onclick="openNotificationModal({{ $notification->id }}, '{{ addslashes($notification->message) }}', {{ $notification->is_read ? 'true' : 'false' }}, 'tab-all', '{{ addslashes($notification->type) }}', '{{ addslashes($notification->title) }}')">

                            <div class="flex items-center gap-3 w-2/3">
                                <div class="w-auto h-auto">
                                    <div class="w-10 h-10 rounded-full border border-[#F57D11] overflow-hidden">
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

                    <div id="AllNotificationModal"
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
                                    <span id="DateNotificationMessage"
                                        class="text-[#F57D11] font-semibold text-base">date
                                        here</span>
                                </p>
                                </p>
                                {{-- <p id="dateMessage" class="mt-2 text-gray-600 w-full text-wrap">
                                    date here
                                </p> --}}
                                <div class="flex gap-3 items-center justify-end w-full mt-2">
                                    <x-button onClick="showNotificationModal()" label="View" className="!px-8" tertiary
                                        button />
                                    <x-button onClick="closeNotificationModal('tab-all')" label="Close"
                                        className="!px-8" primary button />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Unread Notifications -->
                <section id="tab-content-unread" class="hidden divide-y divide-gray-100 w-full h-60 overflow-auto">
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

                    <div id="UnreadNotificationModal"
                        class="w-full h-full fixed top-0 left-0 z-[100] flex items-center justify-center  overflow-x-hidden overflow-y-auto bg-black bg-opacity-70 hidden transition ease-in duration-500">
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
                                <p id="UnreadContainerDateNotificationMessage"
                                    class="text-sm font-semibold text-gray-600">
                                    Requested DTR:
                                    <span id="UnreadDateNotificationMessage"
                                        class="text-[#F57D11] font-semibold text-base">date
                                        here</span>
                                </p>
                                {{-- <p id="dateMessage" class="mt-2 text-gray-600 w-full text-wrap">
                                    date here
                                </p> --}}
                                <div class="flex gap-3 items-center justify-end w-full mt-2">
                                    <x-button onClick="showNotificationModal()" label="View" className="!px-8" tertiary
                                        button />
                                    <x-button onClick="closeNotificationModal('tab-unread')" label="Close"
                                        className="!px-8" primary button />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Archived Notifications -->
                <section id="tab-content-archived" class="hidden divide-y divide-gray-100 w-full h-60 overflow-auto">
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
                                <span id="ArchiveDateNotificationMessage"
                                    class="text-[#F57D11] font-semibold text-base">date
                                    here</span>
                            </p>
                            {{-- <p id="dateMessage" class="mt-2 text-gray-600 w-full text-wrap">
                                date here
                            </p> --}}
                            <div class="flex gap-3 items-center justify-end w-full mt-2">
                                <x-button onClick="showNotificationModal()" label="View" className="!px-8" tertiary
                                    button />
                                <x-button onClick="closeNotificationModal('tab-archive')" label="Close"
                                    className="!px-8" primary button />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- <h1 class="absolute top-0 z-10 px-3 py-1 rounded bg-[#f56d11] text-white text-sm -left-12">DTR</h1> --}}
    <!-- Profile Dropdown -->
    @php
        $admin_roles = ['admin', 'top_management', 'operations_supervisor', 'assistant_supervisor'];
    @endphp
    @if (in_array(Auth::user()->roles->position, $admin_roles))
        <div class="dropdown relative inline-flex hover:scale-105 transition">
            <span class="group">
                <button type="button" id="dropdown-profile" data-target="dropdown-show-profile"
                    class="dropdown-profile inline-flex w-16 h-16 overflow-hidden rounded-full border-4 border-transparent group-hover:border-[#fdb783]/50"
                    onclick="toggleDropdown()">
                    <img draggable="false" src="{{ \App\Models\File::where(
            'id',
            \App\Models\Profile::where('id', Auth::user()->profile_id)->first()->file_id,
        )->first()->path .
            '?=s100?t=' .
            time() }}" alt="user profile"
                        class="w-full h-full border-2 border-white object-cover bg-white rounded-full">
                </button>
            </span>

            <!-- Dropdown Menu -->
            <div id="dropdown-show-profile"
                class="dropdown-menu-profile hidden rounded-lg shadow-lg border border-gray-300 bg-white absolute top-full right-0 lg:w-72 w-40 divide-y divide-gray-200 text-black">
                @php
                    $menuItems = [
                        'admin.smm*' => ['label' => 'SMM', 'route' => 'admin.smm.dashboard'],
                        'admin.dtr*' => ['label' => 'DTR', 'route' => 'admin.dtr.dashboard'],
                        'admin.web*' => ['label' => 'WEB DEVELOPMENT', 'route' => 'admin.web.dashboard'],
                    ];
                @endphp

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


            </div>
        </div>
    @else
        <img draggable="false" src="{{ \App\Models\File::where(
            'id',
            \App\Models\Profile::where('id', Auth::user()->profile_id)->first()->file_id,
        )->first()->path .
            '?=s100?t=' .
            time() }}" alt="user profile" class="w-16 h-16 object-cover bg-white rounded-full border-2 border-white">

    @endif

    <!-- Logout Button -->
    <button type="button" onclick="openLogoutModal()"
        class="text-[#f56d11] bg-white p-1 rounded hover:scale-110 hover:shadow-md transition inline-flex">
        <span class="ant-design--poweroff-outlined w-6 h-6"></span>
    </button>

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

<!-- JavaScript -->
<script>
    // Toggle dropdown
    function toggleDropdown() {
        let dropdown = document.getElementById('dropdown-show-profile');
        dropdown.classList.toggle('hidden');
    }

    // Close dropdown if clicked outside
    document.addEventListener('click', function (event) {
        let dropdown = document.getElementById('dropdown-show-profile');
        let profileButton = document.getElementById('dropdown-profile');

        if (!profileButton.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // Active state for dropdown items
    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', function () {
            document.querySelectorAll('.dropdown-item').forEach(el => el.classList.remove(
                'bg-[#f56d11]', 'text-white'));
            this.classList.add('bg-[#f56d11]', 'text-white');
        });
    });

    // Open and Close Logout Modal
    function openLogoutModal() {
        document.getElementById('logoutModal').classList.remove('hidden');
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').classList.add('hidden');
    }
</script>
<script>
    function openNotificationModal(notificationId, message, isRead, tab, type) {
        const modalId = tab === 'tab-all' ? 'AllNotificationModal' :
            tab === 'tab-unread' ? 'UnreadNotificationModal' :
                'ArchiveNotificationModal';
        const messageElementId = tab === 'tab-all' ? 'allNotificationMessage' :
            tab === 'tab-unread' ? 'unreadNotificationMessage' :
                'archiveNotificationMessage';
        const dateElementId = tab === 'tab-all' ? 'DateNotificationMessage' :
            tab === 'tab-unread' ? 'UnreadDateNotificationMessage' :
                'ArchiveDateNotificationMessage';

        const modal = document.getElementById(modalId);
        const messageElement = document.getElementById(messageElementId);
        const dateElement = document.getElementById(dateElementId);

        if (!modal || !messageElement || !dateElement) {
            console.error("Modal elements not found!");
            return;
        }

        let text = message;
        let msgText;
        let dateText;

        if (type != 'user.dtr.download.request') {
            dateElement.innerText = dateText;
            dateElement.classList.add('opacity-0');
        }

        // Find the last occurrence of "DTR." and extract the message
        let lastIndex = text.lastIndexOf("DTR.");

        if (lastIndex !== -1) {
            msgText = text.substring(0, lastIndex + 4); // Extracts from first word to "DTR."
            dateText = text.substring(lastIndex + 5).trim(); // Extracts everything after "DTR."
        }


        messageElement.innerText = msgText;

        // Show modal
        modal.classList.remove("hidden");

        if (!isRead) {
            markAsRead(notificationId);
        }
    }

    function closeNotificationModal(tab) {
        const modalId = tab === 'tab-all' ? 'AllNotificationModal' :
            tab === 'tab-unread' ? 'UnreadNotificationModal' :
                'ArchiveNotificationModal';

        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add("hidden");
        }
    }

    function markAsRead(notificationId) {
        const app_url = `{{ url('/notifications/${notificationId}/mark-as-read') }}`;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

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

    function archiveNotification(notificationId) {
        const app_url = `{{ url('/notifications/${notificationId}/archive') }}`;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

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
</script>
<script>
    function setupNotifications() {
        let jsonData = @json($notifications);

        // Convert to an array
        let notifications = Object.values(jsonData);

        console.log(notifications);

        const dropdownButton = document.getElementById('dropdown-notification');
        const dropdownMenu = document.getElementById('dropdown-show-notification');
        const tabAllButton = document.getElementById('tab-all');
        const tabUnreadButton = document.getElementById('tab-unread');
        const tabArchivedButton = document.getElementById('tab-archived');
        const tabContentAll = document.getElementById('tab-content-all');
        const tabContentUnread = document.getElementById('tab-content-unread');
        const tabContentArchived = document.getElementById('tab-content-archived');
        const notificationCountSpan = document.getElementById('notification-count');

        function countUnreadNotifications(notifications) {

            let unreadCount = 0;
            for (let i = 0; i < notifications.length; i++) {
                const notification = notifications[i];
                console.log(`Checking notification at index ${i}:`,
                    notification); // Trace: Show the notification being checked

                if (parseInt(notification.is_read) === 0) {
                    unreadCount++;
                    console.log(`Notification at index ${i} is unread. Unread count: ${unreadCount}`); // Trace: Show when a notification is unread
                } else {
                    console.log(`Notification at index ${i} is read.`); // Trace: Show when a notification is read
                }
            }
            console.log(`Total unread notifications: ${unreadCount}`); // Trace: Show the final count
            return unreadCount;
        }

        // Function to update notification count
        function updateNotificationCount() {

            const unreadCount = countUnreadNotifications(notifications);

            if (unreadCount > 0) {
                notificationCountSpan.textContent = `(${unreadCount})`;
            } else {
                notificationCountSpan.textContent = '';
            }

            // update the counter in the button itself
            const buttonCountDiv = dropdownButton.querySelector('div div p');
            if (buttonCountDiv) {
                if (unreadCount <= 99) {
                    buttonCountDiv.textContent = unreadCount;
                } else {
                    buttonCountDiv.textContent = '99+';
                }
            }

            function updateTabCounts(notifications) {
                // All tab count
                let allCount = 0;
                for (let i = 0; i < notifications.length; i++) {
                    const notification = notifications[i];
                    if (parseInt(notification.is_archive) === 0) {
                        allCount++;
                    }
                }
                tabAllButton.textContent = `All (${allCount})`;
                console.log(`All tab count: ${allCount}`);

                // Unread tab count
                let unreadCount = 0;
                for (let i = 0; i < notifications.length; i++) {
                    const notification = notifications[i];
                    if (parseInt(notification.is_read) === 0 && parseInt(notification.is_archive) === 0) {
                        unreadCount++;
                    }
                }
                tabUnreadButton.textContent = `Unread (${unreadCount})`;
                console.log(`Unread tab count: ${unreadCount}`);

                // Archived tab count
                let archivedCount = 0;
                for (let i = 0; i < notifications.length; i++) {
                    const notification = notifications[i];
                    if (parseInt(notification.is_archive) === 1) {
                        archivedCount++;
                    }
                }
                tabArchivedButton.textContent = `Archived (${archivedCount})`;
                console.log(`Archived tab count: ${archivedCount}`);
            }

            // Assuming you have these buttons in your HTML and they are accessible in your JavaScript:
            const tabAllButton = document.getElementById('tab-all');
            const tabUnreadButton = document.getElementById('tab-unread');
            const tabArchivedButton = document.getElementById('tab-archived');

            updateTabCounts(notifications);

            // you can use this in your previous code like this.
            // updateTabCounts(notifications);

        }

        // // Function to toggle dropdown
        // function toggleDropdown() {
        //     dropdownMenu.classList.add("hidden");
        //     dropdownMenu.classList.toggle('hidden');
        // }

        window.addEventListener("click", function (event) {

            debugger
            if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
            }
            else if (!dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.toggle('hidden');
            }

            console.log("Dropdown is now", isHidden ? "visible" : "hidden");
        });



        // Function to show a specific tab
        function showTab(tabId) {
            tabContentAll.classList.add('hidden');
            tabContentUnread.classList.add('hidden');
            tabContentArchived.classList.add('hidden');

            tabAllButton.classList.remove('border-b-2', 'border-[#F57D11]', 'text-[#F57D11]');
            tabAllButton.classList.add('text-gray-500');
            tabUnreadButton.classList.remove('border-b-2', 'border-[#F57D11]', 'text-[#F57D11]');
            tabUnreadButton.classList.add('text-gray-500');
            tabArchivedButton.classList.remove('border-b-2', 'border-[#F57D11]', 'text-[#F57D11]');
            tabArchivedButton.classList.add('text-gray-500');

            if (tabId === 'tab-all') {
                tabContentAll.classList.remove('hidden');
                tabAllButton.classList.add('border-b-2', 'border-[#F57D11]', 'text-[#F57D11]');
                tabAllButton.classList.remove('text-gray-500');
            } else if (tabId === 'tab-unread') {
                tabContentUnread.classList.remove('hidden');
                tabUnreadButton.classList.add('border-b-2', 'border-[#F57D11]', 'text-[#F57D11]');
                tabUnreadButton.classList.remove('text-gray-500');
            } else if (tabId === 'tab-archived') {
                tabContentArchived.classList.remove('hidden');
                tabArchivedButton.classList.add('border-b-2', 'border-[#F57D11]', 'text-[#F57D11]');
                tabArchivedButton.classList.remove('text-gray-500');
            }
        }

        // Function to open notification modal
        window.openNotificationModal = function (notificationId, message, isRead, tab, type, title) {
            console.log("Opening notification modal for ID:", notificationId, "Tab:", tab);

            let modal, messageElement, dateElement, pageTitleElement, titleAttribute;

            debugger

            if (tab === 'tab-all') {
                modal = document.getElementById('AllNotificationModal');
                messageElement = document.getElementById('allNotificationMessage');
                dateElement = document.getElementById('DateNotificationMessage');
                pageTitleElement = document.getElementById('pageTitle');
                containerDateElement = document.getElementById('ContainerDateNotificationMessage');
                console.log("Tab is 'tab-all'. Modal:", modal, "Message Element:", messageElement, "Date Element:",
                    dateElement);
            } else if (tab === 'tab-unread') {
                modal = document.getElementById('UnreadNotificationModal');
                messageElement = document.getElementById('unreadNotificationMessage');
                dateElement = document.getElementById('UnreadDateNotificationMessage');
                pageTitleElement = document.getElementById('unReadPageTitle');
                containerDateElement = document.getElementById('UnreadContainerDateNotificationMessage');
                console.log("Tab is 'tab-unread'. Modal:", modal, "Message Element:", messageElement, "Date Element:", dateElement);
            } else if (tab === 'tab-archive') {
                modal = document.getElementById('ArchiveNotificationModal');
                messageElement = document.getElementById('archiveNotificationMessage');
                dateElement = document.getElementById('ArchiveDateNotificationMessage');
                pageTitleElement = document.getElementById('archivePageTitle');
                containerDateElement = document.getElementById('ArchiveContainerDateNotificationMessage');
                console.log("Tab is 'tab-archive'. Modal:", modal, "Message Element:", messageElement, "Date Element:", dateElement);
            }
            if (modal && messageElement) {
                console.log("Modal and elements found.");
                console.log("Notifications array:", notifications);

                const notification = notifications.find(n => n.id === notificationId);
                console.log("Found notification:", notification);

                if (notification) {
                    pageTitleElement.innerHTML = notification.title;
                    messageElement.textContent = notification.message;
                    console.log("Notification message:", notification.message);

                    const notificationDate = new Date(notification.created_at);
                    console.log("Notification created_at:", notification.created_at, "Parsed date:",
                        notificationDate);

                    if (!isNaN(notificationDate)) {
                        if (type == 'user.dtr.download.request') {
                            containerDateElement.classList.remove('opacity-0');
                            dateElement.textContent = notificationDate.toLocaleDateString();
                            console.log("Formatted date:", dateElement.textContent);
                        } else {
                            containerDateElement.classList.add('opacity-0');
                        }
                    } else {

                        console.error("Invalid date format:", notification.created_at);
                    }

                    modal.classList.remove('hidden');
                    console.log("Modal shown.");

                    if (!isRead) {
                        console.log("Marking notification as read:", notificationId);
                        markNotificationAsRead(notificationId);
                    }
                } else {
                    console.error("Notification not found for ID:", notificationId);
                }
            } else {
                console.error("Modal or elements not found for tab:", tab);
            }
        };

        // Function to close notification modal
        window.closeNotificationModal = function (tab) {
            let modal;
            if (tab === 'tab-all') {
                modal = document.getElementById('AllNotificationModal');
            } else if (tab === 'tab-unread') {
                modal = document.getElementById('UnreadNotificationModal');
            } else if (tab === 'tab-archive') {
                modal = document.getElementById('ArchiveNotificationModal');
            }

            if (modal) {
                modal.classList.add('hidden');
            }
        };

        // Function to mark notification as read
        function markNotificationAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            })
                .then(response => {
                    if (response.ok) {
                        const notification = notifications.find(n => n.id === notificationId);
                        if (notification) {
                            notification.is_read = 1;
                            updateNotificationCount();
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function setupNotificationModalButtons() {
            const showNotificationModalButtons = document.querySelectorAll('[onClick="showNotificationModal()"]');

            showNotificationModalButtons.forEach(button => {
                button.addEventListener('click', function () {
                    showNotificationModal(); // Call the showNotificationModal function
                });
            });
        }

        // Assuming you have a showNotificationModal function defined elsewhere
        function showNotificationModal() {
            // Your logic to show the notification modal
            console.log("Show Notification Modal Function Called");
            window.location.href = `{{ route('admin.dtr.approvals') }}`; // Example:
            // document.getElementById('yourModalId').classList.remove('hidden');
        }

        // Call the setup function when the DOM is ready
        document.addEventListener('DOMContentLoaded', setupNotificationModalButtons);

        // Function to archive notification
        window.archiveNotification = function (notificationId) {
            fetch(`/notifications/${notificationId}/archive`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            })
                .then(response => {
                    if (response.ok) {
                        const notification = notifications.find(n => n.id === notificationId);
                        if (notification) {
                            notification.is_archive = 1;
                            updateNotificationCount();
                            //reloads the page to reflect the changes.
                            location.reload();
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        };

        // Event listeners
        //dropdownButton.addEventListener('click', toggleDropdown);
        tabAllButton.addEventListener('click', () => showTab('tab-all'));
        tabUnreadButton.addEventListener('click', () => showTab('tab-unread'));
        tabArchivedButton.addEventListener('click', () => showTab('tab-archived'));

        // Initial setup
        updateNotificationCount();
        showTab('tab-all');
    }

    setupNotifications();
</script>