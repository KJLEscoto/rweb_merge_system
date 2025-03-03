<head>
    <title>{{ env('APP_NAME') }} | DTR | Schools</title>
</head>

<x-main-layout breadcumb="DTR" page="Schools">
    {{-- @php
        $schools = [
            [
                'id' => 1,
                'name' => 'STI College Davao',
                'image' => 'resources/img/school-logo/sti.png',
                'is_featured' => true,
            ],
            [
                'id' => 2,
                'name' => 'Ateneo De Davao University',
                'image' => 'resources/img/school-logo/addu.png',
                'is_featured' => true,
            ],
            [
                'id' => 3,
                'name' => 'Holy Cross of Davao College',
                'image' => 'resources/img/school-logo/hcdc.png',
                'is_featured' => true,
            ],
            [
                'id' => 4,
                'name' => 'University of Mindanao',
                'image' => 'resources/img/school-logo/um.png',
                'is_featured' => true,
            ],
        ];
    @endphp --}}

    <main class="w-full h-auto flex flex-col lg:!gap-7 gap-5 px-16 py-10">
        @if (session('success'))
                <x-modal.flash-msg msg="success" />
                @elseif (session('update'))
                    <x-modal.flash-msg msg="update" />
                @elseif ($errors->has('invalid'))
                    <x-modal.flash-msg msg="invalid" />
                @elseif (session('invalid'))
                    <x-modal.flash-msg msg="invalid" />
                @endif
        @if ($schools->isNotEmpty())
            <div class="w-full flex items-center justify-between">
                <x-button label="Add School" button primary className="px-8" routePath="admin.dtr.schools.create" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($schools as $school)
                    <a href="{{ route('admin.dtr.schools.show', $school['id']) }}"
                        class="relative bg-white rounded-xl shadow-md lg:!p-7 p-4 flex flex-col items-center justify-between cursor-pointer border border-gray-200 group animate-transition hover:border-[#F57D11]">

                        <div class="flex flex-col gap-5 items-center w-full h-full">
                            <div class="w-auto h-auto">
                                <div class="w-auto h-20 overflow-hidden">
                                    <x-image path="{{ $school['image'] }}" className="w-full h-full" />
                                </div>
                            </div>
                            <h2 class="text-lg truncate w-full font-semibold group-hover:text-[#F57D11] animate-transition text-center">
                                {{ $school['name'] }}
                            </h2>
                        </div>

                        @if ($school['is_featured'] == 'on')
                            <div class="absolute top-3 left-0">
                                <span class="text-white bg-[#F57D11] rounded-r-lg px-5 py-1 text-sm">Featured</span>
                            </div>
                        @endif
                    </a>
                @endforeach
            </div>
        @else
            <div class="w-full h-full flex items-center justify-center flex-col gap-10">
                <h1 class="text-3xl italic font-semibold">No Schools Yet.</h1>
                <img draggable="false" class="w-auto h-80" src="{{ asset('image/revisions_empty.png') }}">
                <a href="{{ route('admin.dtr.schools.create') }}"
                    class="bg-[#f56d11] hover:scale-105 transition text-white px-3 py-2 text-sm rounded font-semibold shadow-md w-fit flex items-center gap-1">
                    <span class="ic--round-add w-5 h-5"></span>
                    Add School
                </a>
            </div>
        @endif
    </main>
</x-main-layout>

{{-- #F53C11 - red --}}
{{-- #F57D11 - orange --}}
