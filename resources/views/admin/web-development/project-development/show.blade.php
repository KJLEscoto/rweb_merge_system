<head>
    <title>{{ env('APP_NAME') }} | Front-end | Approval Form</title>
</head>

<x-main-layout breadcumb="Front-end / Project Development" page="Approval Form">
    <main class="h-auto w-full flex flex-col gap-5 px-10 py-10">
        <div class="p-10 relative w-full h-auto bg-white shadow-lg rounded-md flex flex-col gap-5">

            <div class="absolute right-5 -top-4">
                <div class="w-auto h-auto flex items-center justify-center p-2 rounded bg-[#f56d11] text-white">
                    <span class="mdi--file-check w-5 h-5"></span>
                </div>
            </div>

            <section class="grid grid-cols-2 gap-7">
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Project Name</h1>
                    <p>RWS.028 - FINAL HOMEPAGE DESIGN - APPROVAL</p>
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Designation</h1>
                    <p>Developer</p>
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Project Test Site Link</h1>
                    <div>
                        <a href="https://rwebserver.com/spes/public/admin/login" target="_blank"
                            class="text-blue-500 underline underline-offset-2">Test Site Link</a>
                    </div>
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Google Drive Links</h1>
                    <div>
                        <a href="https://drive.google.com/drive/folders/10mOW5DiWqlwWYaGkT3nOjclrwG8T8VSt?usp=sharing"
                            target="_blank" class="text-blue-500 underline underline-offset-2">
                            Link here.
                        </a>
                    </div>
                </div>
                <div class="space-y-1">
                    <h1 class="font-bold text-xs">Date</h1>
                    <p>03/30/25</p>
                </div>
            </section>

            <!-- E-signature Section -->
            <div>
                <div class="flex items-center gap-3">
                    <span class="font-semibold text-xs">Please leave your e-signature below:</span>

                    <!-- Toggle Switch -->
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" class="sr-only peer" id="signatureToggle">
                            <div class="w-10 h-5 bg-gray-300 rounded-full transition"></div>
                            <div
                                class="absolute left-1 top-1 w-3 h-3 bg-white rounded-full shadow-md transition-all peer-checked:translate-x-5">
                            </div>
                        </div>
                    </label>
                </div>

                <!-- Signature Canvas -->
                <div id="signatureCanvasContainer" class="mt-2">
                    <canvas id="signatureCanvas" class="border rounded-md w-full h-28 bg-gray-100"></canvas>
                </div>

                <!-- Image Upload (Hidden by Default) -->
                <div id="signatureUploadContainer" class="mt-2 hidden">
                    <input type="file" id="signatureImage" accept="image/*" class="border rounded-md p-2 w-full">
                </div>
            </div>

            <!-- Terms and Conditions -->
            <div class="flex items-center">
                <input type="checkbox" id="terms" class="h-4 w-4 checked:accent-[#f56d11] border-gray-300 rounded">
                <label for="terms" class="ml-2 text-sm">
                    I agree to the <a href="#"
                        class="text-[#f56d11] hover:underline underline-offset-2 font-medium">terms and
                        conditions</a>.
                </label>
            </div>

            <!-- Confirm Button -->
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.front-end.project-development') }}"
                    class="border hover:border-[#f56d11] text-[#f56d11] transition flex items-center gap-1 px-3 py-2 text-sm rounded font-semibold w-fit">
                    <span class="eva--arrow-back-fill w-4 h-4"></span>
                    Back
                </a>
                <button
                    class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit">
                    Confirm
                </button>
            </div>

        </div>
    </main>

    <script>
        document.getElementById('signatureToggle').addEventListener('change', function() {
            const canvasContainer = document.getElementById('signatureCanvasContainer');
            const uploadContainer = document.getElementById('signatureUploadContainer');

            if (this.checked) {
                canvasContainer.classList.add('hidden');
                uploadContainer.classList.remove('hidden');
            } else {
                canvasContainer.classList.remove('hidden');
                uploadContainer.classList.add('hidden');
            }
        });
    </script>
</x-main-layout>
