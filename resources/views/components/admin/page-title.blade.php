@props(['breadcumb', 'page'])

<section class="flex flex-col gap-3 select-none">
    <p class="text-xs font-medium">{{ $breadcumb }} / {{ $page }}</p>
    <div class="flex items-center relative">
        <div class="w-10 h-10 rounded-xl bg-[#fdb783] absolute -left-4"></div>
        <p class="relative z-10 text-xl font-bold">{{ $page }}</p>
    </div>
</section>
