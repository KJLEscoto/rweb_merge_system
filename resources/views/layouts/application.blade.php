<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FINAL SMM</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">
    <div class="container mx-auto max-w-screen-2xl">
        <!-- Toggle Sidebar Button for Mobile -->
        <i id="toggleButton" class="sticky top-10 z-20 left-5 bg-[#fa7011] px-3 py-2 opacity-70 rounded-full  text-2xl cursor-pointer fa-solid fa-bars lg:hidden"></i>
        <div class="flex h-screen">
            <!-- Sidebar & Overlay -->
            <div id="sidebarBackdrop" class="fixed inset-0 z-10 bg-black bg-opacity-50 hidden lg:hidden"></div>

            <!-- Sidebar -->
            <div id="sidebar"
            class="fixed top-0 left-0 w-80 md:w-96 z-20 bg-white shadow-lg h-screen overflow-y-auto lg:sticky
                -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0">

                <div class="p-6 flex items-center justify-between">
                    <img class="w-48 sm:w-48 lg:w-64 lg:mx-auto" src="{{asset('/Assets/logo.png')}}" draggable="false" alt="Logo">
                    <i id="closeSidebar" class="text-2xl cursor-pointer fa-solid fa-xmark lg:hidden"></i>
                </div>
                <hr class="border border-[#EC690F] mb-10">
                <x-sidebar />
            </div>


             <!-- Main Content -->
             <div class="flex-1 overflow-y-auto scrollbar-hide bg-gray-100">
                <div class="h-24">
                    <x-navbar :header="View::yieldContent('header')" />
                </div>
                <div class="mt-5 flex-1 overflow-auto">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebar = document.getElementById("sidebar");
            const sidebarBackdrop = document.getElementById("sidebarBackdrop");
            const toggleButton = document.getElementById("toggleButton");
            const closeSidebar = document.getElementById("closeSidebar");

            function openSidebar() {
                sidebar.classList.remove("-translate-x-full");
                sidebarBackdrop.classList.remove("hidden");
            }

            function closeSidebarFunction() {
                sidebar.classList.add("-translate-x-full");
                sidebarBackdrop.classList.add("hidden");
            }

            toggleButton.addEventListener("click", openSidebar);
            closeSidebar.addEventListener("click", closeSidebarFunction);
            sidebarBackdrop.addEventListener("click", closeSidebarFunction);
        });
    </script>
</body>
</html>

<style>
    .scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}

</style>
