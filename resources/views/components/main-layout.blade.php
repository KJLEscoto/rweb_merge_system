<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }}</title>

    {{-- website icon --}}
    <link rel="icon" href="{{ asset('/image/rweb_icon.png') }}" type="image/x-icon">

    {{-- for dev purpose --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- for deployment (tailwindconfig) --}}
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}

    {{-- font --}}
    {{-- <style>
        @font-face {
            font-family: "Proxima Nova";
            src: ("{{ asset('fonts/proximanova_regular.ttf') }}") format("truetype");
            font-weight: normal;
            font-style: normal;
        }
    </style> --}}


    {{-- custom tailwind css here: --}}
    {{-- <script>
        tailwind.config = {
            theme: {
                screens: {
                    'sm': '640px',
                    'md': '768px',
                    'lg': '1024px',
                    'xl': '1280px',
                    '2xl': '1536px',
                },
                fontFamily: {
                    'proxima-nova': ["Proxima Nova", "sans-serif"],
                },
                extend: {
                    colors: {

                    },
                },
            }
        }
    </script> --}}

    <style>
        .text-dim-blue {
            color: #1f2835;
        }

        .bg-light-orange {
            background-color: #f58e12;
        }

        .bg-custom-orange {
            background-color: #f56d11;
        }

        hr {
            color: #f56d11;
        }
    </style>

    {{-- svg icons --}}
    <style>
        .mdi--file-cog {
            display: inline-block;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23000' d='M6 2c-1.11 0-2 .89-2 2v16a2 2 0 0 0 2 2h6.68a7 7 0 0 1-.68-3a7 7 0 0 1 7-7a7 7 0 0 1 1 .08V8l-6-6zm7 1.5L18.5 9H13zM18 14a.26.26 0 0 0-.26.21l-.19 1.32c-.3.13-.59.29-.85.47l-1.24-.5c-.11 0-.24 0-.31.13l-1 1.73c-.06.11-.04.24.06.32l1.06.82a4.2 4.2 0 0 0 0 1l-1.06.82a.26.26 0 0 0-.06.32l1 1.73c.06.13.19.13.31.13l1.24-.5c.26.18.54.35.85.47l.19 1.32c.02.12.12.21.26.21h2c.11 0 .22-.09.24-.21l.19-1.32c.3-.13.57-.29.84-.47l1.23.5c.13 0 .26 0 .33-.13l1-1.73a.26.26 0 0 0-.06-.32l-1.07-.82c.02-.17.04-.33.04-.5s-.01-.33-.04-.5l1.06-.82a.26.26 0 0 0 .06-.32l-1-1.73c-.06-.13-.19-.13-.32-.13l-1.23.5c-.27-.18-.54-.35-.85-.47l-.19-1.32A.236.236 0 0 0 20 14zm1 3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5c-.84 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }

        .ic--baseline-space-dashboard {
            display: inline-block;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23000' d='M11 21H5c-1.1 0-2-.9-2-2V5c0-1.1.9-2 2-2h6zm2 0h6c1.1 0 2-.9 2-2v-7h-8zm8-11V5c0-1.1-.9-2-2-2h-6v7z'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }

        .mynaui--menu {
            display: inline-block;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='none' stroke='%23000' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M4.5 6.5h15M4.5 12h15m-15 5.5h15'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }
    </style>
</head>

<body class="text-dim-blue tracking-wide font-proxima-nova">

    {{-- admin / front-end --}}
    @if (Request::routeIs('admin.front-end.dashboard') || Request::routeIs('admin.front-end.project-development'))
        <main class="grid grid-cols-12 w-full h-auto">
            <aside class="col-span-3 h-[calc(100vh)] w-full overflow-auto p-5 shadow-xl">
                <div class="flex items-center gap-5 justify-start">
                    <span class="mynaui--menu w-7 h-7"></span>
                    <img src="{{ asset('image/rweb_logo.png') }}" class="w-auto h-16">
                </div>
                <div class="my-7 bg-custom-orange h-[0.1px] rounded-full w-full"></div>
                <x-sidebar-menu icon="ic--baseline-space-dashboard" label="Dashboard"
                    routeName="admin.front-end.dashboard" />
                <x-sidebar-menu icon="mdi--file-cog" label="Project Development"
                    routeName="admin.front-end.project-development" />
            </aside>
            <section class="col-span-9 h-auto w-full bg-gray-100">
                {{ $slot }}
            </section>
        </main>
    @else
        <main>
            {{ $slot }}
        </main>
    @endif
</body>

</html>
