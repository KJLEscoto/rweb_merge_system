<head>
    <title>{{ env('APP_NAME') }} | Web Development | Create Draft</title>

    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <style>
        /* Ensure CKEditor is scrollable with max height */
        .ck-editor__editable {
            max-height: 500px !important;
            overflow-y: auto !important;
        }
    </style>
</head>

<x-main-layout breadcumb="Web Development / Task" page="Create Draft">
    <form action="#" method="POST"
        class="bg-white p-6 rounded border-l-8 border-[#F57D11] shadow-md flex flex-col gap-5">
        @csrf
        @method('PUT')

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.web.task') }}"
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
                <p>Title here</p>
            </div>
            <div class="space-y-1 w-full">
                <h1 class="font-bold text-xs">Client</h1>
                <p>Client here</p>
            </div>
            <section class="flex items-start gap-5 w-full">
                <div class="space-y-1 w-full">
                    <h1 class="font-bold text-xs">Date Started</h1>
                    <p>date here</p>
                </div>
                <div class="space-y-1 w-full">
                    <h1 class="font-bold text-xs">Date Target</h1>
                    <p>date here</p>
                </div>
            </section>
            <div class="space-y-1 w-full">
                <h1 class="font-bold text-xs">Instruction</h1>
                <p class="p-3 rounded-sm border">test instruction</p>
            </div>
            <hr class="border border-[#f56d11]">
            <div class="space-y-1 w-full">
                <h1 class="font-bold text-xs">Draft</h1>
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
</x-main-layout>
