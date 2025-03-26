<head>
    <title>{{ env('APP_NAME') }} | Web Development | Edit Draft</title>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <style>
        /* Ensure CKEditor is scrollable with max height */
        .ck-editor__editable {
            max-height: 500px !important;
            overflow-y: auto !important;
        }
    </style>
</head>

@php
    //for employee is $employee
    //for project channel is $web_project_channel
@endphp

<x-main-layout breadcumb="Web Development / Track / View Track" page="Edit Draft">
    <form
        action="{{ route('admin.web.track.draft.edit.post', ['project_id' => $web_project_channel->project_id, 'project_channel_id' => $web_project_channel->id, 'user_id' => $web_project_channel->user_id]) }}"
        method="POST" class="bg-white p-6 rounded border-l-8 border-[#F57D11] shadow-md flex flex-col gap-5">
        @csrf
        @method('PUT')

        <div class="flex items-center gap-3">
            @if (Route::is('admin.web.direct-job-order.*'))
                <a href="{{ route('admin.web.direct-job-order.track.show', ['id' => $web_project_channel->project_id]) }}"
                    class="border hover:border-[#f56d11] text-[#f56d11] transition flex items-center gap-1 px-3 py-2 text-sm rounded font-semibold w-fit">
                    <span class="eva--arrow-back-fill w-4 h-4"></span>
                    Back
                </a>
            @else
                <a href="{{ route('admin.web.track.show', ['id' => $web_project_channel->project_id]) }}"
                    class="border hover:border-[#f56d11] text-[#f56d11] transition flex items-center gap-1 px-3 py-2 text-sm rounded font-semibold w-fit">
                    <span class="eva--arrow-back-fill w-4 h-4"></span>
                    Back
                </a>
            @endif
            <button type="submit"
                class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit">
                Save Changes
            </button>
        </div>

        <div class="space-y-5 lg:p-10 p-7 border rounded">
            <div class="space-y-1 w-full">
                <h1 class="font-bold text-xs">Title</h1>
                <input type="text" name="title" id="title"
                    value="{{ old('title', \App\Models\WebProject::where('id', $web_project_channel->project_id)->first() ? \App\Models\WebProject::where('id', $web_project_channel->project_id)->first()->title : '') }}"
                    class="border px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11]">
            </div>
            <div class="space-y-1 w-full">
                <h1 class="font-bold text-xs">Employee</h1>
                <select name="employee_id" id="employee_id"
                    class="border px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11]">
                    <option value="" disabled>Select Employee</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id', $web_project_channel->employee_id) == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <section class="flex lg:flex-row flex-col gap-5 w-full">
                <div class="space-y-1 w-full">
                    <h1 class="font-bold text-xs">Date Started</h1>
                    <input type="date" name="date_started" id="date_started"
                        value="{{ old('date_started', $web_project_channel->date_started ? \Carbon\Carbon::parse($web_project_channel->date_started)->format('Y-m-d') : '') }}"
                        class="border px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11]">
                </div>
                <div class="space-y-1 w-full">
                    <h1 class="font-bold text-xs">Date Target</h1>
                    <input type="date" name="date_target" id="date_target"
                        value="{{ old('date_target', $web_project_channel->date_target ? $web_project_channel->date_target->format('Y-m-d') : '') }}"
                        class="border px-2 py-1 rounded-sm w-full outline-none focus:ring-2 focus:ring-[#f56d11]">
                </div>
            </section>
            <div class="space-y-1 w-full">
                <h1 class="font-bold text-xs">Instructions</h1>
                <textarea name="instructions" id="editor"
                    class="w-full border-gray-200 rounded-lg">{{ old('instructions', \App\Models\WebProject::where('id', $web_project_channel->project_id)->first() ? \App\Models\WebProject::where('id', $web_project_channel->project_id)->first()->instructions : '') }}</textarea>
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