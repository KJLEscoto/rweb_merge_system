<head>
    <title>{{ env('APP_NAME') }} | DTR | Add History</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ config('app.url') }}">

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <!-- Include Select2 CSS & JS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <!-- Add jQuery and Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

</head>

<x-main-layout breadcumb="DTR / History" page="Add History">

    <div class="h-auto w-full flex flex-col gap-5 px-10 pt-10">
        <form id="historyForm" action="{{ route('admin.dtr.history.create.post') }}" method="POST"
            class="bg-white p-6 shadow-lg flex flex-col gap-5 border border-gray-200">
            @csrf

            @if (session('success'))
                <x-modal.flash-msg msg="success" />
            @elseif (session('update'))
                <x-modal.flash-msg msg="update" />
            @elseif ($errors->has('invalid'))
                <x-modal.flash-msg msg="invalid" />
            @elseif (session('invalid'))
                <x-modal.flash-msg msg="invalid" />
            @endif

            <div class="border w-full p-5 flex justify-between items-end">


                <div class="">
                    <div class="flex justify-end items-center gap-3">
                        <a href="{{ route('admin.dtr.history') }}"
                            class="border hover:border-[#f56d11] text-[#f56d11] transition flex items-center gap-1 px-3 py-2 text-sm rounded font-semibold w-fit">
                            <span class="eva--arrow-back-fill w-4 h-4"></span>
                            Back
                        </a>
                        <button type="submit"
                            class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit">
                            Submit
                        </button>
                    </div>
                </div>

                <div class="w-1/2">
                    <label class="block text-sm font-medium text-gray-700">Select Intern</label>

                    <select id="userSelect" name="user_fullname[]"
                        class="user-select w-full px-5 border rounded-lg focus:ring-[#F57D11] focus:border-[#F57D11]"
                        required>
                        <option value="" disabled selected>Select a user</option>
                        @foreach ($users as $user)
                            @if ($user->role != 'admin')
                                @php
                                    $userImg =
                                        optional(\App\Models\File::find($user->profiles->file_id))->path .
                                            '?t=' .
                                            time() ??
                                        '';
                                @endphp
                                <option value="{{ $user->id }}" data-img="{{ $userImg }}">
                                    {{ $user->firstname }} {{ $user->lastname }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div id="userHistoriesContainer" class="flex gap-5 overflow-x-auto">
                <div id="historyContainer" class="flex flex-nowrap gap-5 mt-4 w-full">
                    <div
                        class="hidden history-card flex flex-col h-fit gap-3 p-4 border rounded-lg shadow-md bg-white relative min-w-[300px]">
                        <div class="flex flex-col gap-2 px-3 pt-3">
                            <label class="block text-sm font-medium text-gray-700">User Full Name</label>

                            <select id="userSelect" name="user_fullname[]"
                                class="user-select w-full p-4 border rounded-lg focus:ring-[#F57D11] focus:border-[#F57D11]">
                                <option value="" disabled selected>Select a user</option>
                                @foreach ($users as $user)
                                    @if ($user->role != 'admin')
                                        @php
                                            $userImg =
                                                optional(\App\Models\File::find($user->profiles->file_id))->path .
                                                    '?t=' .
                                                    time() ??
                                                '';
                                        @endphp
                                        <option value="{{ $user->id }}" data-img="{{ $userImg }}">
                                            {{ $user->firstname }} {{ $user->lastname }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>

                        </div>
                    </div>
                </div>
            </div>
    </div>
    </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const userHistoriesContainer = document.getElementById("userHistoriesContainer");

            document.addEventListener("change", function(event) {
                if (event.target.classList.contains("user-select")) {
                    let userId = event.target.value;
                    let selectedOption = event.target.options[event.target.selectedIndex];
                    let userName = selectedOption.text;
                    let userImg = selectedOption.getAttribute("data-img") || "";

                    let imgElement = event.target.previousElementSibling; // Ensure the image element exists
                    if (imgElement) {
                        imgElement.src = userImg ||
                            "https://via.placeholder.com/40"; // Use a default image if empty
                        imgElement.classList.remove("hidden");
                    }

                    let existingContainer = document.querySelector(`[data-user-id="${userId}"]`);

                    if (!existingContainer) {
                        let userContainer = document.createElement("div");
                        userContainer.className =
                            "flex h-fit flex-col gap-5 p-4 border rounded-lg shadow-md bg-white relative";
                        userContainer.setAttribute("data-user-id", userId);
                        userContainer.innerHTML = `
                    <h3 class="text-lg font-semibold text-[#F57D11] flex flex-col items-center gap-2">
                        <img src="${userImg}" class="w-14 h-14 rounded-full" alt="${userName}">
                        <p class='text-wrap text-sm text-center capitalize'>${userName}</p>
                    </h3>
                    <div class="history-container flex flex-col gap-5"></div>
                    <div class="flex justify-between mt-2">
                        <button type="button" class="add-card text-green-500 font-bold">+ Add</button>
                        <button type="button" class="remove-user text-red-500 font-bold">- Remove</button>
                    </div>
                `;
                        userHistoriesContainer.appendChild(userContainer);
                    }
                }
            });

            document.addEventListener("click", function(event) {
                if (event.target.classList.contains("add-card")) {
                    let userContainer = event.target.closest("[data-user-id]");
                    let userId = userContainer.getAttribute("data-user-id");
                    let container = userContainer.querySelector(".history-container");

                    if (container) {
                        let newCard = document.createElement("div");
                        newCard.className =
                            "history-card h-fit flex flex-col gap-3 p-4 border rounded-lg shadow-md bg-white relative";
                        newCard.innerHTML = `
                    <button type="button" class="remove-card text-red-500 absolute top-2 right-2 font-bold">✖</button>
                    <input type="hidden" name="user_fullname[]" value="${userId}">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <select name="history_description[${userId}][]" class="w-full p-2 border rounded-lg focus:ring-[#F57D11] focus:border-[#F57D11] text-gray-500" required>
                        <option value="Time In">Time In</option>
                        <option value="Time In | Late">Time In | Late</option>
                        <option value="Time Out">Time Out</option>
                    </select>
                    <label class="block text-sm font-medium text-gray-700">DateTime</label>
                    <input type="datetime-local" name="history_datetime[${userId}][]" class="w-full p-2 border rounded-lg focus:ring-[#F57D11] focus:border-[#F57D11] text-gray-500" required>
                `;
                        container.appendChild(newCard);
                    }
                }

                if (event.target.classList.contains("remove-card")) {
                    event.target.closest(".history-card").remove();
                }

                if (event.target.classList.contains("remove-user")) {
                    event.target.closest("[data-user-id]").remove();
                }
            });

            $(document).ready(function() {
                function formatUser(user) {
                    if (!user.id) return user.text;
                    let img = $(user.element).data("img") || "https://via.placeholder.com/40";
                    return $(
                        `<span class="flex items-center gap-2">
                    <img src="${img}" class="w-12 h-12 rounded-full" />
                    ${user.text}
                </span>`
                    );
                }

                $("#userSelect").select2({
                    templateResult: formatUser,
                    templateSelection: formatUser,
                });
            });
        });
    </script>
</x-main-layout>
