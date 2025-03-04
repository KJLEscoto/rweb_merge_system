<head>
    <title>{{ env('APP_NAME') }} | DTR | Add School</title>
</head>

<x-main-layout breadcumb="DTR / Schools" page="Add School">
    <main class="px-10 pt-10">
        <div class="rounded bg-white border-l-8 border-[#f56d11] h-auto w-full flex flex-col gap-5 p-5">
            <form action="{{ route('admin.dtr.schools.create.post') }}" method="POST" enctype="multipart/form-data"
                class="flex flex-col gap-5">

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.dtr.schools') }}"
                        class="border hover:border-[#f56d11] text-[#f56d11] transition flex items-center gap-1 px-3 py-2 text-sm rounded font-semibold w-fit">
                        <span class="eva--arrow-back-fill w-4 h-4"></span>
                        Back
                    </a>
                    <button
                        class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit">
                        Create
                    </button>
                </div>
                @csrf

                <div class="flex items-start gap-5 w-full">
                    <div class="flex flex-col gap-5 w-full">
                        <div class="space-y-1 w-full">
                            <label class="font-bold text-xs" for="name">School Name</label>
                            <input type="text" name="name" id="name" required
                                class="border border-gray-300 px-2 py-1.5 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none">
                        </div>

                        <label class="flex items-center space-x-2 cursor-pointer">
                            <span class="text-sm text-gray-500">Featured to login</span>
                            <div class="relative">
                                <input type="checkbox" class="sr-only peer" name="is_featured">
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

                        <input type="file" name="file" accept="image/*" required
                            class="border border-gray-300 px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11] focus:outline-none"
                            onchange="previewImage(event)">
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
