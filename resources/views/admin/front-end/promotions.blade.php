<head>
    <title>{{ env('APP_NAME') }} | Front-end | Promotions</title>
</head>

<x-main-layout breadcumb="Front-end" page="Promotions">
    <main class="h-auto w-full flex flex-col gap-5 px-16 pt-10 pb-20">
        <div class="h-full w-full flex gap-5">
            <div class="h-auto w-2/3 flex flex-col gap-5">
                <div class="h-[220px] w-full overflow-hidden shadow-lg">
                    <img src="{{ asset('image/banner.png') }}" alt="image"
                        class="object-cover w-full h-full bg-gray-300">
                </div>
            </div>
            <div class="w-1/3 h-fit shadow-lg">
                <div class="h-40 w-full overflow-hidden bg-gray-300 border group relative">
                    {{-- <img class="h-full w-full object-cover" src="{{ asset('image/rweb_posting.png') }}" alt="image"> --}}
                    <video autoplay class="h-full w-full object-cover"
                        src="{{ asset('videos/rweb_video.mp4') }}"></video>
                    <div
                        class="opacity-0 group-hover:opacity-100 absolute bg-black/20 inset-0 transition-all flex items-center justify-center duration-200 ease-in">
                        <button type="button" onclick="togglePlay()"
                            class="rounded-full p-2 bg-[#f56d11] text-white m-auto hover:scale-105 transition">
                            <span class="mingcute--play-fill w-5 h-5"></span>
                        </button>
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

    <script>
        function togglePlay() {
            const video = document.querySelector("video");
            if (video.paused) {
                video.play();
            } else {
                video.pause();
            }
        }
    </script>

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
