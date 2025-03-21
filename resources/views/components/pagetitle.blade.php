@props(['header' => ''])
<div class="m-10 px-6 col-span-2">
    <p class="text-nowrap text-[0.7rem]">Pages / {{$header}}</p>
    <div class="relative mt-3">
        <!-- Background box -->
        <div class="absolute w-10 h-8 bg-[#fbb787] rounded-md top-[1px] left-[-10px] z-0"></div>
        <!-- Text -->
        <h1 class="text-nowrap relative text-lg lg:text-2xl font-bold z-10">{{$header}}</h1>
    </div>
</div>