<head>
    <title>{{ env('APP_NAME') }} | DTR | School Detail</title>
</head>

<x-main-layout breadcumb="DTR / Schools" page="School Detail">
    <main class="px-10 pt-10">
        <div class="bg-white p-5 rounded-lg border-l-8 border-[#f56d11] shadow-md space-y-7">

            @if (session('success'))
                <x-modal.flash-msg msg="success" />
            @elseif (session('update'))
                <x-modal.flash-msg msg="update" />
            @elseif ($errors->has('invalid'))
                <x-modal.flash-msg msg="invalid" />
            @elseif (session('invalid'))
                <x-modal.flash-msg msg="invalid" />
            @endif

            <form action="{{ route('admin.dtr.schools.show.update', $school['id']) }}" method="POST"
                enctype="multipart/form-data" class="flex flex-col gap-5">
                @csrf
                @method('PUT')

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.dtr.schools') }}"
                        class="border hover:border-[#f56d11] text-[#f56d11] transition flex items-center gap-1 px-3 py-2 text-sm rounded font-semibold w-fit">
                        <span class="eva--arrow-back-fill w-4 h-4"></span>
                        Back
                    </a>
                    <button type="submit"
                        class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit">
                        Save Changes
                    </button>
                </div>

                <div class="flex items-start gap-5 w-full">
                    <div class="flex flex-col gap-5 w-full">
                        <div class="space-y-1 w-full">
                            <label class="font-bold text-xs" for="name">School Name</label>
                            <input type="text" name="name" id="name" value="{{ $school['name'] }}"
                                class="border border-gray-300 px-2 py-1.5 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                        </div>

                        <label class="flex items-center space-x-2 cursor-pointer">
                            <span class="text-sm text-gray-500">Featured to login</span>
                            <div class="relative">
                                <input type="checkbox" name="is_featured" class="sr-only peer"
                                    {{ $school['is_featured'] ? 'checked' : '' }}>
                                <div class="w-10 h-5 bg-gray-300 rounded-full peer-checked:bg-[#F57D11] transition">
                                </div>
                                <div
                                    class="absolute left-1 top-1 w-3 h-3 bg-white rounded-full shadow-md transition-all peer-checked:translate-x-5">
                                </div>
                            </div>
                        </label>

                    </div>

                    <div class="space-y-1 w-full">
                        <label class="font-bold text-xs">School Logo</label>

                        <input type="file" name="file" accept="image/*"
                            class="w-full mt-1 p-2 border border-gray-300 rounded-lg focus:ring-[#F57D11] focus:border-[#F57D11] cursor-pointer"
                            onchange="previewImage(event)">
                        @if ($school['image'])
                            <div id="previewContainer" class="w-full my-2">
                                <img id="imagePreview" class="w-auto h-20" src="{{ asset($school['image']) }}">
                            </div>
                        @else
                            <div id="previewContainer" class="hidden w-full my-2">
                                <img id="imagePreview" class="w-auto h-20">
                            </div>
                        @endif
                        <div id="previewContainer" class="hidden w-full my-2">
                            <img id="imagePreview" class="w-auto h-20">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById("imagePreview");
            const previewContainer = document.getElementById("previewContainer");

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove("hidden");
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-main-layout>
