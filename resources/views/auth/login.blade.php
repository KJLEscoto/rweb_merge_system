{{-- admin login --}}
@if (Request::routeIs('show.admin.login'))

    <head>
        <title>{{ env('APP_NAME') }} | Admin Login</title>
    </head>
    <x-main-layout>
        @if (session('success'))
            <x-modal.flash-msg msg="success" />
        @elseif ($errors->has('invalid'))
            <x-modal.flash-msg msg="invalid" />
        @elseif (session('invalid'))
            <x-modal.flash-msg msg="invalid" />
        @endif

        <main class="container mx-auto max-w-screen-xl">
            <div class="flex items-center justify-center h-screen w-full p-10 overflow-auto">
                <form action="{{ route('admin.login') }}" method="POST"
                    class="w-1/2 bg-white rounded-lg p-10 flex flex-col gap-5 h-auto shadow-lg border border-gray-100">
                    @csrf
                    <div class="w-auto h-auto">
                        <img src="{{ asset('image/rweb_logo.png') }}" class="w-auto h-14">
                    </div>

                    <h1 class="font-semibold text-2xl text-center text-[#f56d11]">Admin Login</h1>

                    <input type="hidden" name="type" id="type" value="admin">

                    <x-form.input big label="Email" name_id="email" value="" placeholder="admin@email.com" />

                    <x-form.input big label="Password" type="password" name_id="password" value=""
                        placeholder="••••••••" />

                    <x-button primary submit label="Login" big />
                </form>
            </div>

        </main>
    </x-main-layout>

    {{-- user/intern login --}}
@elseif (Request::routeIs('show.login'))

    <head>
        <title>{{ env('APP_NAME') }} | Intern Login</title>
    </head>

    <x-main-layout>
        <x-modal.forgot-password id="forgot-password-modal" />
        <x-modal.confirmation-email id="confirmation-email-modal" />

        <x-form.container routeName="login" method="POST"
            className="w-full h-full flex items-center justify-center bg-white container mx-auto max-w-screen-2xl">
            <div class="w-full flex flex-col items-center justify-center gap-7 overflow-x-hidden md:!p-10 p-5">
                @if (session('success'))
                    <x-modal.flash-msg msg="success" />
                @elseif ($errors->has('invalid'))
                    <x-modal.flash-msg msg="invalid" />
                @elseif (session('invalid'))
                    <x-modal.flash-msg msg="invalid" />
                @endif

                <div class="w-full">
                    <x-logo />
                </div>

                <x-page-title title="intern login" titleClass="lg:!text-3xl md:!text-2xl !text-xl"
                    vectorClass="lg:!h-6 md:!h-4 !h-3" />

                <div class="w-full flex flex-col gap-5">
                    <x-form.input label="Email" classLabel="font-medium text-2xl" name_id="email"
                        placeholder="example@gmail.com" labelClass="text-xl font-medium" big />

                    <x-form.input label="Password" classLabel="font-medium text-2xl" name_id="password"
                        placeholder="••••••••" type="password" labelClass="text-xl font-medium" big />
                    <x-form.input name_id="type" type="password" hidden big placeholder="••••••••" value="user" />

                    <section class="flex items-center gap-1 -mt-5">
                        <p>Forgot Password?</p>
                        <button type="button" data-pd-overlay="#forgot-password-modal"
                            data-modal-target="forgot-password-modal" data-modal-toggle="forgot-password-modal"
                            class="modal-button font-bold hover:text-[#F57D11] cursor-pointer">Click
                            here.</button>
                    </section>

                    <div>
                        <x-button primary label="Login" submit big />
                    </div>

                    <article class="wrapper py-10 w-full">
                        @php
                            $schools = \App\Models\School::get();
                        @endphp
                        <div class="marquee">
                            <div class="marquee__group">
                                @for ($i = 1; $i <= 3; $i++)
                                    @foreach ($schools as $school)
                                        @if ($school['is_featured'] == 'on')
                                            <x-image className="w-full h-full rounded-lg border shadow"
                                                path="{{ \App\Models\File::where('id', $school['file_id'])->first()['path'] }}" />
                                        @endif
                                    @endforeach
                                @endfor
                            </div>

                            <div aria-hidden="true" class="marquee__group">
                                @for ($i = 1; $i <= 3; $i++)
                                    @foreach ($schools as $school)
                                        @if ($school['is_featured'] == 'on')
                                            <x-image className="w-full h-full rounded-lg border shadow"
                                                path="{{ \App\Models\File::where('id', $school['file_id'])->first()['path'] }}" />
                                        @endif
                                    @endforeach
                                @endfor
                            </div>
                        </div>
                    </article>
                </div>

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
            </div>
        </x-form.container>
    </x-main-layout>
@endif
<script>
    if (window.history.replaceState) {
        window.history.replaceState({}, document.title, window.location.pathname);
    }
</script>

{{-- <x-button tertiary label="Click here." className="modal-button" openModal="forgot-password-modal" button /> --}}
