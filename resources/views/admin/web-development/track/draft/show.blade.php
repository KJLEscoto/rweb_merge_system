<head>
    <title>{{ env('APP_NAME') }} | Web Development | View Draft</title>

    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <style>
        /* Ensure CKEditor is scrollable with max height */
        .ck-editor__editable {
            max-height: 500px !important;
            overflow-y: auto !important;
        }
    </style>
</head>

<x-main-layout breadcumb="Web Development / Task / View Track" page="View Draft">
    <div class="rounded bg-white border-l-8 border-[#f56d11] h-auto w-full flex flex-col gap-5 p-5">
        <a href="{{ route('admin.web.track.show', 1) }}"
            class="border hover:border-[#f56d11] text-[#f56d11] transition flex items-center gap-1 px-3 py-2 text-sm rounded font-semibold w-fit">
            <span class="eva--arrow-back-fill w-4 h-4"></span>
            Back
        </a>
    </div>
</x-main-layout>
