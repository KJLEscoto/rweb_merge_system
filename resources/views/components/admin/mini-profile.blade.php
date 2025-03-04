<div class="flex items-center gap-5 justify-end bg-[#f56d11] text-white px-24 py-6 rounded-bl-[50px] shadow-lg">
    <p>Hi, <span class="capitalize">{{ Auth::user()->firstname }}</span>!</p>
    {{-- <h1 class="absolute top-0 z-10 px-3 py-1 rounded bg-[#f56d11] text-white text-sm -left-12">DTR</h1> --}}
    <!-- Profile Dropdown -->
    <div class="dropdown relative inline-flex hover:scale-105 transition">
        <button type="button" id="dropdown-profile" data-target="dropdown-show-profile"
            class="dropdown-profile inline-flex w-16 h-16 overflow-hidden rounded-full border-4 border-[#fdb783]/50"
            onclick="toggleDropdown()">
            <img draggable="false"
                src="{{ \App\Models\File::where(
                    'id',
                    \App\Models\Profile::where('id', Auth::user()->profile_id)->first()->file_id,
                )->first()->path }}"
                alt="user profile" class="w-full h-full object-cover border-4 rounded-full border-[#fdb783]">
        </button>

        <!-- Dropdown Menu -->
        <div id="dropdown-show-profile"
            class="dropdown-menu-profile hidden rounded-lg shadow-lg border border-gray-300 bg-white absolute top-full right-0 w-72 divide-y divide-gray-200 text-black">
            @php
                $menuItems = [
                    'admin.smm*' => ['label' => 'SMM', 'route' => 'admin.smm.dashboard'],
                    'admin.dtr*' => ['label' => 'DTR', 'route' => 'admin.dtr.dashboard'],
                    'admin.front-end*' => ['label' => 'FRONT-END', 'route' => 'admin.front-end.dashboard'],
                ];
            @endphp

            <ul class="py-2">
                @foreach ($menuItems as $route => $item)
                    <li>
                        <a href="{{ route($item['route']) }}" @class([
                            'block px-6 py-2 font-semibold cursor-pointer',
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
    document.addEventListener('click', function(event) {
        let dropdown = document.getElementById('dropdown-show-profile');
        let profileButton = document.getElementById('dropdown-profile');

        if (!profileButton.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // Active state for dropdown items
    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', function() {
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
