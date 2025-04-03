<head>
    <title>{{ env('APP_NAME') }} | Web Development | Create Direct Job Order</title>

    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <style>
        /* Ensure CKEditor is scrollable with max height */
        .ck-editor__editable {
            max-height: 500px !important;
            overflow-y: auto !important;
        }
    </style>
</head>

<x-main-layout breadcumb="Web Development / Direct Job Order" page="Create Direct Job Order">
    <form action="{{ route('admin.web.direct-job-order.store') }}" method="POST"
        class="bg-white p-6 rounded border-l-8 border-[#F57D11] shadow-md flex flex-col gap-5">
        @csrf

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.web.direct-job-order') }}"
                class="border hover:border-[#f56d11] text-[#f56d11] transition flex items-center gap-1 px-3 py-2 text-sm rounded font-semibold w-fit">
                <span class="eva--arrow-back-fill w-4 h-4"></span>
                Back
            </a>
            <button type="submit"
                class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit">
                Submit
            </button>
        </div>

        <div class="space-y-5 lg:p-10 p-7 border rounded">
            <div class="space-y-1 w-full">
                <h1 class="font-bold text-xs">Title</h1>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                    class="border px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11]">
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-3">
                    <h1 class="font-semibold text-lg text-gray-800">Web Designer</h1>
                    <input type="text" id="webDesignerSearch" placeholder="Search Web Designers..." class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                    <div class="space-y-2 w-full overflow-y-auto max-h-[300px] border rounded-md p-3">
                        @foreach ($employee as $client)
                            @if ($client->roles->position != 'client')
                                <label class="flex items-center space-x-2 web-designer-item">
                                    <input type="checkbox" name="clients[web_designer][]" value="{{ $client->id }}"
                                        {{ in_array($client->id, old('clients.web_designer', [])) ? 'checked' : '' }}
                                        class="form-checkbox text-indigo-600 rounded">
                                    <span class="text-sm text-gray-700">{{ $client->name }}</span>
                                </label>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="space-y-3">
                    <h1 class="font-semibold text-lg text-gray-800">Front-End Developer</h1>
                    <input type="text" id="frontEndSearch" placeholder="Search Front-End Developers..." class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                    <div class="space-y-2 w-full overflow-y-auto max-h-[300px] border rounded-md p-3">
                        @foreach ($employee as $client)
                            @if ($client->roles->position != 'client')
                                <label class="flex items-center space-x-2 front-end-item">
                                    <input type="checkbox" name="clients[front_end][]" value="{{ $client->id }}"
                                        {{ in_array($client->id, old('clients.front_end', [])) ? 'checked' : '' }}
                                        class="form-checkbox text-indigo-600 rounded">
                                    <span class="text-sm text-gray-700">{{ $client->name }}</span>
                                </label>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="space-y-3">
                    <h1 class="font-semibold text-lg text-gray-800">Back-End Developer</h1>
                    <input type="text" id="backEndSearch" placeholder="Search Back-End Developers..." class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                    <div class="space-y-2 w-full overflow-y-auto max-h-[300px] border rounded-md p-3">
                        @foreach ($employee as $client)
                            @if ($client->roles->position != 'client')
                                <label class="flex items-center space-x-2 back-end-item">
                                    <input type="checkbox" name="clients[back_end][]" value="{{ $client->id }}"
                                        {{ in_array($client->id, old('clients.back_end', [])) ? 'checked' : '' }}
                                        class="form-checkbox text-indigo-600 rounded">
                                    <span class="text-sm text-gray-700">{{ $client->name }}</span>
                                </label>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="w-full col-span-2 lg:col-span-1">
                <p class="text-sm text-gray-600">Client</p>
                
                <select name="client_id" class="w-full border text-sm border-gray-200 rounded-lg !px-2 !py-1" required>
                    <option value="">Select a client</option>

                    @foreach ($employee as $client)
                        @if ($client->roles->position == 'client')
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endif
                    @endforeach
                </select>

                @error('role_id')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <section class="flex lg:flex-row flex-col gap-5 w-full">
                <div class="space-y-1 w-full">
                    <h1 class="font-bold text-xs">Date Started</h1>
                    <input type="date" name="date_started" id="date_started" value="{{ old('date_started') }}"
                        class="border px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11]">
                </div>
                <div class="space-y-1 w-full">
                    <h1 class="font-bold text-xs">Date Target</h1>
                    <input type="date" name="date_target" id="date_target" value="{{ old('date_target') }}"
                        class="border px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11]">
                </div>
            </section>
            <div class="space-y-1 w-full">
                <h1 class="font-bold text-xs">Instructions</h1>
                <textarea name="instructions" id="editor" class="w-full border-gray-200 rounded-lg">{{ old('instructions') }}</textarea>
            </div>
        </div>

    </form>

    <script>
        // Initialize CKEditor
        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                console.log('CKEditor initialized');
            })
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Web Designer Search
            document.getElementById('webDesignerSearch').addEventListener('input', function () {
                const searchTerm = this.value.toLowerCase();
                const items = document.querySelectorAll('.web-designer-item');
                items.forEach(item => {
                    const name = item.querySelector('span').textContent.toLowerCase();
                    item.style.display = name.includes(searchTerm) ? 'flex' : 'none';
                });
            });

            // Front-End Developer Search
            document.getElementById('frontEndSearch').addEventListener('input', function () {
                const searchTerm = this.value.toLowerCase();
                const items = document.querySelectorAll('.front-end-item');
                items.forEach(item => {
                    const name = item.querySelector('span').textContent.toLowerCase();
                    item.style.display = name.includes(searchTerm) ? 'flex' : 'none';
                });
            });

            // Back-End Developer Search
            document.getElementById('backEndSearch').addEventListener('input', function () {
                const searchTerm = this.value.toLowerCase();
                const items = document.querySelectorAll('.back-end-item');
                items.forEach(item => {
                    const name = item.querySelector('span').textContent.toLowerCase();
                    item.style.display = name.includes(searchTerm) ? 'flex' : 'none';
                });
            });
        });
    </script>
</x-main-layout>
