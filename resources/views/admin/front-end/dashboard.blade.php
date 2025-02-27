<head>
    <title>{{ env('APP_NAME') }} | Front-end | Dashboard</title>
</head>

<x-main-layout breadcumb="Front-end" page="Dashboard">
    <main class="h-auto w-full flex flex-col gap-5 px-16 py-10">
        <div class="h-full w-full flex gap-5">
            <div class="h-auto w-2/3 flex flex-col gap-5">
                <div class="h-[220px] w-full overflow-hidden shadow-lg">
                    <img src="{{ asset('image/banner.png') }}" alt="image"
                        class="object-cover w-full h-full bg-gray-300">
                </div>
                <div class="flex flex-col gap-3">
                    <section class="flex items-end justify-between w-full">
                        <h1 class="lg:!text-base font-semibold text-sm">Documents</h1>
                        <a href="#" class="text-xs font-medium hover:text-[#f56d11]">See
                            all</a>
                    </section>
                    <section class="w-full h-[300px] bg-white shadow-lg p-5 flex flex-col gap-2 text-sm divide-y">
                        <div class="grid grid-cols-3 font-semibold">
                            <h1>Project Development</h1>
                            <h1>Status</h1>
                            <h1></h1>
                        </div>
                        <div class="grid grid-cols-3 gap-y-3 text-xs h-full overflow-auto py-3">
                            @for ($i = 1; $i <= 12; $i++)
                                <p>Site Map</p>
                                <p>Signed</p>
                                <div class="flex items-center justify-center">
                                    <p
                                        class="bg-[#f56d11] px-2 py-1 rounded text-white font-medium cursor-pointer hover:scale-105 transition-all duration-150 ease-in">
                                        Sign Now
                                    </p>
                                </div>
                            @endfor
                        </div>
                    </section>
                </div>
            </div>
            <div class="w-1/3 h-fit shadow-lg">
                <div class="h-40 w-full overflow-hidden bg-gray-300 border group relative">
                    <img class="h-full w-full object-cover" src="{{ asset('image/rweb_posting.png') }}" alt="image">
                    <div
                        class="opacity-0 group-hover:opacity-100 absolute bg-black/20 inset-0 transition-all flex items-center justify-center duration-200 ease-in">
                        <a href="https://www.facebook.com/photo?fbid=1056711586472150&set=a.427792996030682"
                            target="_blank"
                            class="px-4 py-2 text-xs rounded bg-[#f56d11] text-white m-auto hover:scale-105 transition-all duration-150 ease-in">View
                            Website</a>
                    </div>
                </div>
                <div class="flex gap-2 w-full items-center text-white justify-center bg-white p-3">
                    <x-admin.socials icon="mage--facebook" />
                    <x-admin.socials icon="ri--instagram-fill" />
                    <x-admin.socials icon="formkit--pinterest" />
                </div>
            </div>
        </div>
    </main>

    <article class="wrapper">
        <div class="marquee">
            <div class="marquee__group">
                @for ($i = 1; $i <= 5; $i++)
                    @for ($i = 1; $i <= 4; $i++)
                        <img draggable="false" src="{{ asset('image/carousel-' . $i . '.png') }}"
                            class="!w-full !h-auto rounded-lg shadow-lg">
                    @endfor
                @endfor
            </div>

            <div aria-hidden="true" class="marquee__group">
                @for ($i = 1; $i <= 5; $i++)
                    @for ($i = 1; $i <= 4; $i++)
                        <img draggable="false" src="{{ asset('image/carousel-' . $i . '.png') }}"
                            class="!w-full !h-auto rounded-lg shadow-lg">
                    @endfor
                @endfor
            </div>
        </div>
    </article>

    <style>
        :root {
            --gap: 1rem;
            --duration: 120s;
            --scroll-start: 0;
            --scroll-end: -100%;
        }

        .marquee {
            display: flex;
            overflow: hidden;
            user-select: none;
            gap: var(--gap);
            mask-image: linear-gradient(to right,
                    rgba(0, 0, 0, 0),
                    rgba(0, 0, 0, 1) 20%,
                    rgba(0, 0, 0, 1) 80%,
                    rgba(0, 0, 0, 0));
        }

        .marquee__group {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: space-around;
            gap: var(--gap);
            min-width: 100%;
            animation: scroll-x var(--duration) linear infinite;
        }

        @keyframes scroll-x {
            from {
                transform: translateX(var(--scroll-start));
            }

            to {
                transform: translateX(var(--scroll-end));
            }
        }

        .marquee img {
            height: 60px;
            /* Adjust height as needed */
            width: auto;
        }

        /* Parent wrapper */
        .wrapper {
            display: flex;
            flex-direction: column;
            gap: var(--gap);
            margin: auto;
            max-width: 100vw;
        }
    </style>
</x-main-layout>
