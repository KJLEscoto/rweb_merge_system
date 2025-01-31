<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel9 x Tailwind.v3</title>

    {{-- website icon --}}
    {{-- <link rel="icon" href="{{ asset('/img/logo-header.png') }}" type="image/x-icon"> --}}

    {{-- for dev purpose --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- for deployment (tailwindconfig) --}}
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}

    {{-- custom css here: --}}
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
                    'megrim': ['Megrim', 'sans-serif'],
                    'dosis': ['Dosis', 'sans-serif'],
                },
                extend: {
                    colors: {
                        'custom-green': '#7b7f5d',
                        'custom-blue': '#405060'
                    },
                },
            }
        }
    </script> --}}
</head>

<body>
    <p class="text-red-500 text-5xl font-bold">
        Test
    </p>
</body>

</html>
