<head>
    <title>{{ env('APP_NAME') }} | Web Development | User Details</title>
</head>

<x-main-layout breadcumb="Web Development / Users" page="User Details">
    <div class="rounded bg-white border-l-8 border-[#f56d11] h-auto w-full flex flex-col gap-5 p-5">
        <a href="{{ route('admin.web.users') }}"
            class="border hover:border-[#f56d11] text-[#f56d11] transition flex items-center gap-1 px-3 py-2 text-sm rounded font-semibold w-fit">
            <span class="eva--arrow-back-fill w-4 h-4"></span>
            Back
        </a>
    </div>
</x-main-layout>
