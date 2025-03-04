

@extends('layouts.application')

@section('title', 'Page Title')
@section('header', "Dashboard")

<link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<div class="container mx-auto p-6 max-w-screen-xl overflow-hidden">
    @if(!Auth::user()->signature)
        <form action="{{ url('signature/store') }}" method="POST" id="modalSignatureForm">
            @csrf
            @method('PUT')
            <x-save-signature />
        </form>
    @endif
    <div>
        <div class="grid grid-cols-1 md:grid md:grid-cols-3 md:px-2 mx-auto">
            <div class="col-span-1 md:col-span-2">
                <img class="" src="{{asset('/Assets/Banner.png')}}" alt="" draggable="false">
                <h1 class="mx-6 border-b-2 border-[#fa7011] w-fit">Approvals</h1>
                <div class="px-6 mt-2">
                    <div class="w-full p-4 bg-white rounded-lg shadow-md">
                        <table class="table-auto gap-8 text-left border-collapse w-full">
                            <thead class="text-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-sm font-semibold">Project Development</th>
                                    <th class="px-4 py-2 text-sm font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($job_drafts as $job_draft )
                                    <tr>
                                        <td class="px-4 py-2 text-sm">{{$job_draft->jobOrder->title}} -  {{Str::title(str_replace('_', ' ', $job_draft->type)) }}</td>
                                            <td class="px-4 py-2 text-sm flex items-center gap-8">
                                                @if (auth()->user()->role_id == '1' and $job_draft->status == 'completed')
                                                    Approved
                                                @elseif ((auth()->user()->role_id == '2' && ($job_draft->status == 'completed' || $job_draft->status == 'Submitted to Top Manager' || $job_draft->status == "Submitted to Client" || $job_draft->status == "Submitted to Supervisor")) || (auth()->user()->role_id == '5' && ($job_draft->status == "Submitted to Client" || $job_draft->status == "completed" )) || (auth()->user()->role_id == '6' && ($job_draft->status == "Submitted to Client" || $job_draft->status == "completed" || $job_draft->status == "Submitted to Top Manager")))
                                                    Signed
                                                @elseif ((auth()->user()->role_id == '3' && ($job_draft->status == 'Submitted to Operations' || $job_draft->status == 'completed' || $job_draft->status == 'Submitted to Top Manager' || $job_draft->status == "Submitted to Client")) || (auth()->user()->role_id == '4' && ($job_draft->status == 'Submitted to Operations' || $job_draft->status == 'completed' || $job_draft->status == 'Submitted to Top Manager' || $job_draft->status == "Submitted to Client")))
                                                    Created
                                                    @elseif (auth()->user()->role_id == '1' and $job_draft->status == 'Submitted to Client')
                                                    <a href="{{url('client/show/' . $job_draft->id)}}">
                                                        <p class="text-[#fa7011]">Approve</p>
                                                    </a>
                                                    @elseif (auth()->user()->role_id == '2' and $job_draft->status == 'Submitted to Operations')
                                                    <a href="{{url('operation/show/' . $job_draft->id)}}">
                                                        <p class="text-[#fa7011]">Sign</p>
                                                    </a>
                                                    @elseif (auth()->user()->role_id == '3' and $job_draft->status == 'Waiting for Content Writer Approval' || $job_draft->status == 'Waiting for Graphic Designer Approval')
                                                        <form action="{{ url('content/accept/' . $job_draft->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="text-[#fa7011] bg-transparent border-none cursor-pointer">
                                                                Accept
                                                            </button>
                                                        </form>
                                                    @elseif (auth()->user()->role_id == '3' and $job_draft->status == 'pending')
                                                    <a href="{{url('content/edit/' . $job_draft->id)}}">
                                                        <p class="text-[#fa7011]">Create</p>
                                                    </a>
                                                    @elseif (auth()->user()->role_id == '4' and $job_draft->status == 'Waiting for Content Writer Approval' || $job_draft->status == 'Waiting for Graphic Designer Approval')
                                                    <form action="{{ url('graphic/accept/' . $job_draft->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="text-[#fa7011] bg-transparent border-none cursor-pointer">
                                                            Accept
                                                        </button>
                                                    </form>
                                                    @elseif (auth()->user()->role_id == '4' and $job_draft->status == 'pending')
                                                    <a href="{{url('graphic/edit/' . $job_draft->id)}}">
                                                        <p class="text-[#fa7011]">Create</p>
                                                    </a>
                                                    @elseif (auth()->user()->role_id == '5' and $job_draft->status == 'Submitted to Top Manager')
                                                    <a href="{{url('topmanager/show/' . $job_draft->id)}}">
                                                        <p class="text-[#fa7011]">Sign</p>
                                                    </a>
                                                    @elseif (auth()->user()->role_id == '6' and $job_draft->status == 'Submitted to Supervisor')
                                                    <a href="{{url('supervisor/approve/show/' . $job_draft->id)}}">
                                                        <p class="text-[#fa7011]">Sign</p>
                                                    </a>
                                                @endif
                                                @if (($job_draft->status =='completed' && auth()->user()->role_id == '1') || (($job_draft->status =='Submitted to Operations' || $job_draft->status =='Submitted to Top Manager' || $job_draft->status =='Submitted to Client' || $job_draft->status =='completed')  && (auth()->user()->role_id == '3' || auth()->user()->role_id == '4')) || (auth()->user()->role_id == "2" &&  ($job_draft->status == "Submitted to Top Manager" || $job_draft->status == "Submitted to Client" || $job_draft->status == "Submitted to Supervisor" || $job_draft->status == "completed")) || (auth()->user()->role_id == "5" &&  ($job_draft->status == "Submitted to Client" || $job_draft->status == "completed")) || (auth()->user()->role_id == '6' && ($job_draft->status == "Submitted to Client" || $job_draft->status == "completed" || $job_draft->status == "Submitted to Top Manager")))
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                                @endif
                                            </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="grid grid-cols-2 mt-4">
                    <div class="col-span-2 mb-4 lg:mb-0 lg:cols-span-1">
                        @if (auth()->user()->role_id == 3 || auth()->user()->role_id == 4 || auth()->user()->role_id == 2 || auth()->user()->role_id == 6)
                        <h1 class="mx-6 border-b-2 border-[#fa7011] w-fit">Revisions</h1>
                        <div class="px-6 mt-2">
                                <div class="w-full p-4 bg-white rounded-lg shadow-md">
                                    <table class="table-auto gap-8 text-left border-collapse w-full">
                                        <thead class="text-gray-700">
                                            <tr>
                                                <th class="px-4 py-2 text-sm font-semibold">Project Development</th>
                                                <th class="px-4 py-2 text-sm font-semibold">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($job_drafts_revisions as $job_draft_revision )
                                                <tr>
                                                    <td class="px-4 py-2 text-sm">{{$job_draft_revision->jobOrder->title}} - {{Str::title(str_replace('_', ' ', $job_draft_revision->type))}}</td>
                                                    <td class="px-4 py-2 text-sm">
                                                        @if (auth()->user()->role_id == 3)
                                                            <a href="{{url('revision/edit/' . $job_draft_revision->id)}}">
                                                                <p class="text-[#fa7011]">Revise</p>
                                                            </a>

                                                        @elseif (auth()->user()->role_id == 4)
                                                            <a href="{{url('revision/edit/' . $job_draft_revision->id)}}">
                                                                <p class="text-[#fa7011]">Revise</p>
                                                            </a>

                                                        @elseif (auth()->user()->role_id == 2)
                                                            <a href="{{url('revision/edit/' . $job_draft_revision->id)}}">
                                                                <p class="text-[#fa7011]">Revise</p>
                                                            </a>

                                                        @elseif (auth()->user()->role_id == 6)
                                                            <a href="{{url('revision/edit/' . $job_draft_revision->id)}}">
                                                                <p class="text-[#fa7011]">Revise</p>
                                                            </a>
                                                        @endif
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        @endif
                    </div>

                    <div class="col-span-2 lg:cols-span-1">
                        @if (auth()->user()->role_id == 2 || auth()->user()->role_id == 6)
                        <h1 class="mx-6 border-b-2 border-[#fa7011] w-fit">Tasks</h1>
                        <div class="px-6 mt-2">
                                <div class="w-full p-4 bg-white rounded-lg shadow-md">
                                    <table class="table-auto gap-8 text-left border-collapse w-full">
                                        <thead class="text-gray-700">
                                            <tr>
                                                <th class="px-4 py-2 text-sm font-semibold">Project Development</th>
                                                <th class="px-4 py-2 text-sm font-semibold">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($my_tasks as $my_task )
                                                @if ($my_task->contentWriter->name == Auth::user()->name && $my_task->type == "content_writer" || $my_task->graphicDesigner->name == Auth::user()->name && $my_task->type == "graphic_designer")
                                                    <tr>
                                                        <td class="px-4 py-2 text-sm" 
                                                            id="taskType-{{$my_task->id}}" 
                                                            data-type="{{$my_task->type}}">
                                                            {{$my_task->jobOrder->title}} - {{$my_task->type}}
                                                        </td>
                                                        <td class="px-4 py-2 text-sm">
                                                            @if (auth()->user()->role_id == 2)
                                                                @if ($my_task->status == "Waiting for Content Writer Approval" || $my_task->status == "Waiting for Graphic Designer Approval")
                                                                <form action="{{ url('operation/task/accept/' . $my_task->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit" class="text-[#fa7011] bg-transparent border-none cursor-pointer">
                                                                        Accept
                                                                    </button>
                                                                </form>
                                                                
                                                                @else
                                                                    <a href="{{url('operation/task/edit/' . $my_task->id)}}">
                                                                        <p class="text-[#fa7011]">Create</p>
                                                                    </a>
                                                                @endif
                                                
                                                            @elseif (auth()->user()->role_id == 6)
                                                                @if ($my_task->status == "Waiting for Content Writer Approval" || $my_task->status == "Waiting for Graphic Designer Approval")
                                                                    <form action="{{ url('supervisor/task/accept/' . $my_task->id) }}" method="POST">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <button type="submit" class="text-[#fa7011] bg-transparent border-none cursor-pointer">
                                                                            Accept
                                                                        </button>
                                                                    </form>
                                                                  
                                                                @else
                                                                    <a href="{{url('supervisor/task/edit/' . $my_task->id)}}">
                                                                        <p class="text-[#fa7011]">Create</p>
                                                                    </a>
                                                                @endif
                                                             
                                                            @endif
                                                        </td>

                                                    </tr>
                                                
                                                @endif
                        
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        @endif
                    </div>
                </div>
            </div>
            <div class="flex flex-col items-center">
                <div class="mt-10 w-full flex flex-col justify-center items-center shadow-lg">
                    <div class="w-full h-full">
                        <iframe width="100%" height="100%"
                            src="{{url('https://www.youtube.com/embed/QF-HFO7Uop0?si=APB2sG6Xrdm-C-ct')}}"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                    <div class="h-20 w-full bg-white flex items-center justify-center gap-8">
                        <i class="fa-brands fa-facebook-f px-3 py-2 rounded-full bg-[#fa7011]"
                            style="color: #ffffff;"></i>
                        <i class="fa-brands fa-instagram px-3 py-2 rounded-full bg-[#fa7011]"
                            style="color: #ffffff;"></i>
                        <i class="fa-brands fa-pinterest-p px-3 py-2 rounded-full bg-[#fa7011]"
                            style="color: #ffffff;"></i>
                    </div>
                </div>
            </div>
            {{-- Bottom Part --}}
            <div class="max-w-screen-xl pt-10">
                <div class="carousel">
                    <div class="carousel-track flex">
                        <a class="carousel-item">
                            <img src="{{asset('/Assets/ads1.png')}}" alt="Image 1" draggable="false">
                        </a>
                        <a class="carousel-item">
                            <img src="{{asset('/Assets/ads2.png')}}" alt="Image 2" draggable="false">
                        </a>
                        <a class="carousel-item">
                            <img src="{{asset('/Assets/ads3.png')}}" alt="Image 3" draggable="false">
                        </a>
                        <a class="carousel-item">
                            <img src="{{asset('/Assets/ads4.png')}}" alt="Image 4" draggable="false">
                        </a>
                        <a class="carousel-item">
                            <img src="{{asset('/Assets/ads1.png')}}" alt="Image 1" draggable="false">
                        </a>
                        <a class="carousel-item">
                            <img src="{{asset('/Assets/ads2.png')}}" alt="Image 2" draggable="false">
                        </a>
                        <a class="carousel-item">
                            <img src="{{asset('/Assets/ads3.png')}}" alt="Image 3" draggable="false">
                        </a>
                        <a class="carousel-item">
                            <img src="{{asset('/Assets/ads4.png')}}" alt="Image 4" draggable="false">
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

<style>
    .carousel {
        @apply relative overflow-hidden w-full mx-auto;
    }

    .carousel-track {
        @apply flex gap-0;
        width: calc(8 * 24rem); /* Increase width */
        animation: scroll 60s linear infinite;
    }

    .carousel-item {
        @apply flex-shrink-0 w-[400px]; /* Increase width */
    }

    .carousel-item img {
        @apply object-cover w-full h-[300px]; /* Increase height */
    }

    /* Keyframes for infinite scroll */
    @keyframes scroll {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-100%);
        }
    }

    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('td[id^="taskType-"]').forEach(element => {
            const type = element.getAttribute('data-type');
            if (type) {
                const formatted = type.replace(/_/g, ' ') // Replace underscores with spaces
                                     .replace(/\b\w/g, char => char.toUpperCase()); // Capitalize each word
                element.textContent = element.textContent.split('-')[0] + '- ' + formatted;
            }
        });

            // Get the carousel track
            const carouselTrack = document.querySelector('.carousel-track');

            // Duplicate images logic
            const items = [...carouselTrack.children];
            const firstSetWidth = items.length / 2 * items[0].offsetWidth;

            // Reset scroll on animation end
            carouselTrack.addEventListener('animationiteration', () => {
                carouselTrack.style.transform = 'translateX(0)';
            });

            // Restart animation
            carouselTrack.style.animation = 'none';
            setTimeout(() => {
                carouselTrack.style.animation = '';
            }, 10);

        });
    </script>

