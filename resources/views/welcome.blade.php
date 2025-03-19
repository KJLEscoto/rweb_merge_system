{{-- <x-main-layout>
    <main class="h-[calc(100vh)] overflow-auto w-full bg-white flex flex-col items-center justify-center py-5">
        <div id="model" class="w-auto h-auto">
            <x-image className="w-[500px] h-auto filter drop-shadow-xl"
                path="{{ asset('resources/img/hero-model-blob.png') }}" />
        </div>
        <div id="letters" class="flex flex-col gap-3 text-center">
            <h1 class="lg:!text-5xl md:text-3xl text-lg text-[#F57D11] font-bold">Daily Time Record | OJT</h1>
            <p class="lg:!text-lg text-sm text-gray-600">Track Your Internship Hours with Ease!</p>
        </div>
        <div id="buttons" class="flex items-center gap-5 mt-10">
            <x-button big primary label="Create Account" routePath="show.register" />
            <x-button big tertiary label="Log In" className="font-semibold" routePath="show.login" />
        </div>
    </main>

    <script>
        gsap.registerPlugin(ScrollTrigger);
        gsap.fromTo("#model", {
            opacity: 0.5,
            y: -120
        }, {
            opacity: 1,
            y: 0,
            duration: 1.5
        });

        gsap.fromTo("#letters", {
            opacity: 0.2,
        }, {
            opacity: 1,
            duration: 2
        });

        gsap.fromTo("#buttons", {
            opacity: 0.5,
            y: 120
        }, {
            opacity: 1,
            y: 0,
            duration: 1.5
        });
    </script>

</x-main-layout>
--}}

<x-main-layout>
    <main class="overflow-hidden">
        <div class="lg:h-auto h-screen w-full bg-white lg:grid lg:grid-cols-12 relative">
            <section id="right-side"
                class="lg:h-[calc(100vh)] h-full lg:py-0 py-10 overflow-auto col-span-8 lg:flex justify-center items-center">
                <div class="container mx-auto max-w-screen-xl">
                    <div class="w-full lg:px-20 px-10 space-y-20">
                        <div class="space-y-10 w-full">
                            <div class="w-40 h-2 bg-gradient-to-r from-[#F57D11] via-[#F57D11]/90 to-[#F53C11]"></div>

                            <div class="space-y-5">
                                <section class="flex items-end gap-1">
                                    <img class="w-auto lg:h-16 h-10" src="{{ asset('image/rweb_icon.png') }}">
                                    <h1 class="font-bold lg:text-5xl md:text-3xl text-xl text-gray-700">Web System</h1>
                                </section>
                                <p class="text-gray-600 lg:w-2/3 lg:text-base text-sm">Streamlining operations with an
                                    all-in-one
                                    system for
                                    <span class="font-bold">SMM</span>,
                                    <span class="font-bold">Web Development</span>,
                                    and <span class="font-bold">OJT Daily Time
                                        Records</span>.
                                </p>
                            </div>

                            <div class="space-y-3 w-full">
                                <h1 class="font-semibold lg:text-lg text-base text-gray-700">For Intern (OJT)</h1>
                                <div class="flex items-center gap-5 w-full">
                                    <a href="{{ route('show.login') }}"
                                        class="px-8 py-3 rounded relative overflow-hidden font-medium text-white flex items-center justify-center gap-2 animate-transition bg-gradient-to-r from-[#F57D11] via-[#F57D11]/70 to-[#F53C11] hover:bg-[#F53C11] disabled:opacity-50 lg:text-sm text-xs cursor-pointer">
                                        Login
                                    </a>
                                    <a href="{{ route('show.register') }}"
                                        class="px-8 py-3 border rounded text-[#F57D11] hover:border-[#F57D11] animate-transition flex items-center w-fit justify-center gap-2 lg:text-sm text-xs cursor-pointer font-medium">
                                        Create Account
                                    </a>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <h1 class="font-semibold lg:text-lg text-base text-gray-700">SMM Client</h1>
                                <a href="{{ route('admin.smm.login') }}"
                                    class="px-8 py-3 w-fit rounded relative overflow-hidden font-medium text-white flex items-center justify-center gap-2 animate-transition bg-gradient-to-r from-[#F57D11] via-[#F57D11]/70 to-[#F53C11] hover:bg-[#F53C11] disabled:opacity-50 lg:text-sm text-xs cursor-pointer">
                                    Login here
                                    <span class="eva--arrow-forward-outline w-5 h-5"></span>
                                </a>
                            </div>
                            <div class="space-y-3">
                                <h1 class="font-semibold lg:text-lg text-base text-gray-700">Web Development Client</h1>
                                <a href="{{ route('web.login') }}"
                                    class="px-8 py-3 w-fit rounded relative overflow-hidden font-medium text-white flex items-center justify-center gap-2 animate-transition bg-gradient-to-r from-[#F57D11] via-[#F57D11]/70 to-[#F53C11] hover:bg-[#F53C11] disabled:opacity-50 lg:text-sm text-xs cursor-pointer">
                                    Login here
                                    <span class="eva--arrow-forward-outline w-5 h-5"></span>
                                </a>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <section
                                class="p-2 w-fit h-fit flex items-center justify-center rounded-full text-[#F57D11] hover:border-[#F57D11] transition cursor-pointer border border-gray-200">
                                <span class="w-5 h-5 mage--facebook"></span>
                            </section>
                            <section
                                class="p-2 w-fit h-fit flex items-center justify-center rounded-full text-[#F57D11] hover:border-[#F57D11] transition cursor-pointer border border-gray-200">
                                <span class="w-5 h-5 ri--instagram-fill"></span>
                            </section>
                            <section
                                class="p-2 w-fit h-fit flex items-center justify-center rounded-full text-[#F57D11] hover:border-[#F57D11] transition cursor-pointer border border-gray-200">
                                <span class="w-5 h-5 formkit--pinterest"></span>
                            </section>
                        </div>
                    </div>
                </div>
            </section>

            <section id="left-side"
                class="lg:h-[calc(100vh)] lg:block hidden h-full shadow-xl scale-x-[-1] col-span-4 bg-center bg-no-repeat bg-cover "
                style="background-image: url('resources/img/diamond-pattern.png')">
            </section>
        </div>
    </main>
</x-main-layout>

<script>
    gsap.registerPlugin(ScrollTrigger);

    gsap.fromTo("#right-side", {
        opacity: 0.5,
        x: -120
    }, {
        opacity: 1,
        x: 0,
        duration: 1.5
    });

    gsap.fromTo("#left-side", {
        opacity: 0.5,
        x: 120
    }, {
        opacity: 1,
        x: 0,
        duration: 1.5
    });
</script>