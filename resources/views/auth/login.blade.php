{{-- admin login --}}
@if (Request::routeIs('show.admin.login'))
    <x-main-layout>
        @if (session('success'))
            <x-flash-msg msg="success" />
        @elseif ($errors->has('invalid'))
            <x-flash-msg msg="invalid" />
        @elseif (session('invalid'))
            <x-flash-msg msg="invalid" />
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

                    <x-form.input big label="Email" name_id="email_address" value=""
                        placeholder="admin@email.com" />

                    <x-form.input big label="Password" name_id="password" value="" placeholder="••••••••" />

                    <x-button primary submit label="Login" big />
                </form>
            </div>

        </main>
    </x-main-layout>

@endif
