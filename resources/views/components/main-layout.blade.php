<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }}</title>

    {{-- website icon --}}
    <link rel="icon" href="{{ asset('/image/rweb_icon.png') }}" type="image/x-icon">

    {{-- for development purpose --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- for production (tailwindconfig) --}}
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}

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

    {{-- colors --}}
    <style>
        .gradient {
            background: linear-gradient(to right, #F57D11, rgba(245, 125, 17, 0.7), #F53C11);
            transition: background 0.3s ease-in;
        }

        .gradient:hover {
            background: #F53C11;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 0.5rem;
            /* Equivalent to w-2 */
            height: 0.5rem;
            /* Equivalent to h-2 */
        }

        /* Scrollbar track */
        ::-webkit-scrollbar-track {
            background-color: #D1D5DB;
            /* Equivalent to bg-gray-300 */
        }

        /* Scrollbar thumb */
        ::-webkit-scrollbar-thumb {
            background-color: #6B7280;
            /* Equivalent to bg-gray-500 */
        }

        /* Scrollbar thumb hover state */
        ::-webkit-scrollbar-thumb:hover {
            background-color: #000000;
            /* Equivalent to bg-black */
        }

        /* #1f2835 - dim blue */
        /* #f58e12 - light orange */
        /* #f56d11 - main color */
    </style>

    {{-- svg icons --}}
    <style>
        .tdesign--file-download-filled {
            display: inline-block;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 25'%3E%3Cpath fill='%23000' d='M13 1H3v22h10.876A6.5 6.5 0 0 1 21 12.814V9h-8z'/%3E%3Cpath fill='%23000' d='M21 7v-.414L15.414 1H15v6zm-1 14.11V14h-2v7.11l-2.508-2.48l-1.406 1.422L19 24.91l4.914-4.858l-1.406-1.422z'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }

        .mdi--file-check {
            display: inline-block;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23000' d='m23.5 17l-5 5l-3.5-3.5l1.5-1.5l2 2l3.5-3.5zM6 2c-1.11 0-2 .89-2 2v16c0 1.11.89 2 2 2h7.81c-.53-.91-.81-1.95-.81-3c0-3.31 2.69-6 6-6c.34 0 .67.03 1 .08V8l-6-6m-1 1.5L18.5 9H13Z'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }

        .meteor-icons--search {
            display: inline-block;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cg fill='none' stroke='%23000' stroke-linecap='round' stroke-linejoin='round' stroke-width='2'%3E%3Ccircle cx='10' cy='10' r='7'/%3E%3Cpath d='m15 15l6 6'/%3E%3C/g%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }

        .fa--user {
            display: inline-block;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1280 1536'%3E%3Cpath fill='%23000' d='M1280 1271q0 109-62.5 187t-150.5 78H213q-88 0-150.5-78T0 1271q0-85 8.5-160.5t31.5-152t58.5-131t94-89T327 704q131 128 313 128t313-128q76 0 134.5 34.5t94 89t58.5 131t31.5 152t8.5 160.5m-256-887q0 159-112.5 271.5T640 768T368.5 655.5T256 384t112.5-271.5T640 0t271.5 112.5T1024 384'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }

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

        .fluent--clipboard-text-edit-48-filled {
            display: inline-block;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 48 48'%3E%3Cpath fill='%23000' d='M31.813 7h3.937A4.25 4.25 0 0 1 40 11.25v10.752c-1.54.005-3.077.6-4.243 1.784L26.685 33H16.25a1.25 1.25 0 1 0 0 2.5h7.974l-1.979 2.01a5 5 0 0 0-1.277 2.252l-.838 3.235q-.13.51-.132 1.003H12.25A4.25 4.25 0 0 1 8 39.75v-28.5A4.25 4.25 0 0 1 12.25 7h3.937a4.25 4.25 0 0 1 4.063-3h7.5a4.25 4.25 0 0 1 4.063 3M18.5 8.25c0 .966.784 1.75 1.75 1.75h7.5a1.75 1.75 0 1 0 0-3.5h-7.5a1.75 1.75 0 0 0-1.75 1.75m-3.5 12c0 .69.56 1.25 1.25 1.25h15.5a1.25 1.25 0 1 0 0-2.5h-15.5c-.69 0-1.25.56-1.25 1.25M16.25 26a1.25 1.25 0 1 0 0 2.5h7.5a1.25 1.25 0 1 0 0-2.5zm26.584-.832a3.98 3.98 0 0 0-5.652.022L23.671 38.913a3 3 0 0 0-.767 1.351l-.838 3.234c-.383 1.477.961 2.82 2.437 2.438l3.235-.839a3 3 0 0 0 1.351-.766L42.812 30.82a3.98 3.98 0 0 0 .022-5.651'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }

        .ic--baseline-discount {
            display: inline-block;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23000' d='M12.79 21L3 11.21v2c0 .53.21 1.04.59 1.41l7.79 7.79c.78.78 2.05.78 2.83 0l6.21-6.21c.78-.78.78-2.05 0-2.83z'/%3E%3Cpath fill='%23000' d='M11.38 17.41c.78.78 2.05.78 2.83 0l6.21-6.21c.78-.78.78-2.05 0-2.83L12.63.58A2.04 2.04 0 0 0 11.21 0H5C3.9 0 3 .9 3 2v6.21c0 .53.21 1.04.59 1.41zM7.25 3a1.25 1.25 0 1 1 0 2.5a1.25 1.25 0 0 1 0-2.5'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }

        .streamline--manual-book-solid {
            display: inline-block;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 14 14'%3E%3Cpath fill='%23000' fill-rule='evenodd' d='M12 10.75h-.25v1.75H12a.75.75 0 0 1 0 1.5H3.625a2.375 2.375 0 0 1-2.375-2.375V2.25A2.25 2.25 0 0 1 3.5 0h7a2.25 2.25 0 0 1 2.25 2.25V10a.75.75 0 0 1-.75.75m-8.375 0h6.625v1.75H3.625a.875.875 0 0 1 0-1.75m3.546-7.921a.875.875 0 0 0-1.046.858a.625.625 0 1 1-1.25 0a2.125 2.125 0 1 1 2.75 2.031a.625.625 0 0 1-1.25-.031v-.5c0-.345.28-.625.625-.625a.875.875 0 0 0 .17-1.733ZM7 8.884a.875.875 0 1 1 0-1.75a.875.875 0 0 1 0 1.75' clip-rule='evenodd'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }

        .ant-design--poweroff-outlined {
            display: inline-block;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1024 1024'%3E%3Cpath fill='%23000' d='M705.6 124.9a8 8 0 0 0-11.6 7.2v64.2c0 5.5 2.9 10.6 7.5 13.6a352.2 352.2 0 0 1 62.2 49.8c32.7 32.8 58.4 70.9 76.3 113.3a355 355 0 0 1 27.9 138.7c0 48.1-9.4 94.8-27.9 138.7a355.9 355.9 0 0 1-76.3 113.3a353.1 353.1 0 0 1-113.2 76.4c-43.8 18.6-90.5 28-138.5 28s-94.7-9.4-138.5-28a353.1 353.1 0 0 1-113.2-76.4A355.9 355.9 0 0 1 184 650.4a355 355 0 0 1-27.9-138.7c0-48.1 9.4-94.8 27.9-138.7c17.9-42.4 43.6-80.5 76.3-113.3c19-19 39.8-35.6 62.2-49.8c4.7-2.9 7.5-8.1 7.5-13.6V132c0-6-6.3-9.8-11.6-7.2C178.5 195.2 82 339.3 80 506.3C77.2 745.1 272.5 943.5 511.2 944c239 .5 432.8-193.3 432.8-432.4c0-169.2-97-315.7-238.4-386.7M480 560h64c4.4 0 8-3.6 8-8V88c0-4.4-3.6-8-8-8h-64c-4.4 0-8 3.6-8 8v464c0 4.4 3.6 8 8 8'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }

        .mage--facebook {
            display: inline-block;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23000' d='M9.602 21.026v-7.274H6.818a.545.545 0 0 1-.545-.545V10.33a.545.545 0 0 1 .545-.545h2.773V7a4.547 4.547 0 0 1 4.86-4.989h2.32a.556.556 0 0 1 .557.546v2.436a.557.557 0 0 1-.557.545h-1.45c-1.566 0-1.867.742-1.867 1.833v2.413h3.723a.533.533 0 0 1 .546.603l-.337 2.888a.545.545 0 0 1-.545.476h-3.364v7.274a.96.96 0 0 1-.975.974h-1.937a.96.96 0 0 1-.963-.974'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }

        .ri--instagram-fill {
            display: inline-block;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23000' d='M13.028 2c1.125.003 1.696.009 2.189.023l.194.007c.224.008.445.018.712.03c1.064.05 1.79.218 2.427.465c.66.254 1.216.598 1.772 1.153a4.9 4.9 0 0 1 1.153 1.772c.247.637.415 1.363.465 2.428c.012.266.022.487.03.712l.006.194c.015.492.021 1.063.023 2.188l.001.746v1.31a79 79 0 0 1-.023 2.188l-.006.194c-.008.225-.018.446-.03.712c-.05 1.065-.22 1.79-.466 2.428a4.9 4.9 0 0 1-1.153 1.772a4.9 4.9 0 0 1-1.772 1.153c-.637.247-1.363.415-2.427.465l-.712.03l-.194.006c-.493.014-1.064.021-2.189.023l-.746.001h-1.309a78 78 0 0 1-2.189-.023l-.194-.006a63 63 0 0 1-.712-.031c-1.064-.05-1.79-.218-2.428-.465a4.9 4.9 0 0 1-1.771-1.153a4.9 4.9 0 0 1-1.154-1.772c-.247-.637-.415-1.363-.465-2.428l-.03-.712l-.005-.194A79 79 0 0 1 2 13.028v-2.056a79 79 0 0 1 .022-2.188l.007-.194c.008-.225.018-.446.03-.712c.05-1.065.218-1.79.465-2.428A4.9 4.9 0 0 1 3.68 3.678a4.9 4.9 0 0 1 1.77-1.153c.638-.247 1.363-.415 2.428-.465c.266-.012.488-.022.712-.03l.194-.006a79 79 0 0 1 2.188-.023zM12 7a5 5 0 1 0 0 10a5 5 0 0 0 0-10m0 2a3 3 0 1 1 .001 6a3 3 0 0 1 0-6m5.25-3.5a1.25 1.25 0 0 0 0 2.5a1.25 1.25 0 0 0 0-2.5'/%3E%3C/svg%3E");
            background-color: currentColor;
            -webkit-mask-image: var(--svg);
            mask-image: var(--svg);
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            -webkit-mask-size: 100% 100%;
            mask-size: 100% 100%;
        }

        .formkit--pinterest {
            display: inline-block;
            --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='%23000' d='M8.34 1C5.46 1 2.62 2.92 2.62 6.02c0 1.97 1.11 3.1 1.78 3.1c.28 0 .44-.77.44-.99c0-.26-.66-.82-.66-1.9c0-2.26 1.72-3.85 3.94-3.85c1.91 0 3.32 1.09 3.32 3.08c0 1.49-.6 4.28-2.53 4.28c-.7 0-1.3-.5-1.3-1.23c0-1.06.74-2.09.74-3.18c0-1.86-2.63-1.52-2.63.72c0 .47.06.99.27 1.42c-.39 1.67-1.18 4.15-1.18 5.87c0 .53.08 1.05.13 1.58c.1.11.05.1.19.04c1.41-1.94 1.36-2.31 2-4.85c.35.66 1.24 1.01 1.94 1.01c2.98 0 4.32-2.9 4.32-5.52c0-2.79-2.41-4.6-5.05-4.6'/%3E%3C/svg%3E");
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

<body class="text-[#1f2835] tracking-wide">

    {{-- admin / front-end --}}
    @if (Request::routeIs('admin.front-end.dashboard') || Request::routeIs('admin.front-end.project-development'))
        @props(['breadcumb', 'page'])
        <main class="grid grid-cols-12 w-full h-auto">
            <aside class="col-span-3 h-[calc(100vh)] sticky top-0 w-full overflow-auto p-5 shadow-xl">
                <div class="flex items-center gap-5 justify-start">
                    <span class="mynaui--menu w-7 h-7"></span>
                    <img src="{{ asset('image/rweb_logo.png') }}" class="w-auto h-16">
                </div>
                <div class="my-7 bg-[#f56d11] h-[0.1px] rounded-full w-full"></div>
                <x-sidebar-menu icon="ic--baseline-space-dashboard" label="Dashboard"
                    routeName="admin.front-end.dashboard" />
                <x-sidebar-menu icon="mdi--file-cog" label="Project Development"
                    routeName="admin.front-end.project-development" />
                <x-sidebar-menu icon="fa--user" label="Profile" routeName="admin.front-end.project-development" />
                <x-sidebar-menu icon="fluent--clipboard-text-edit-48-filled" label="Revision Checklist"
                    routeName="admin.front-end.project-development" />
                <x-sidebar-menu icon="ic--baseline-discount" label="Promotions"
                    routeName="admin.front-end.project-development" />
                <x-sidebar-menu icon="streamline--manual-book-solid" label="Instruction Manual"
                    routeName="admin.front-end.project-development" />
            </aside>
            <section class="col-span-9 h-auto w-full bg-gray-100 pb-10">
                <div class="px-16 pt-16">
                    <headers class="w-full flex items-end justify-between">
                        <x-admin.page-title breadcumb="{{ $breadcumb }}" page="{{ $page }}" />

                        <section class="absolute right-0 top-0">
                            <x-admin.mini-profile />
                        </section>
                    </headers>
                </div>

                {{ $slot }}
            </section>
        </main>
    @else
        <main class="bg-white h-auto w-full">
            {{ $slot }}
        </main>
    @endif
</body>

</html>
