
@extends('layouts.application')

@section('title', 'Clients')
@section('header', 'List of Job Orders to Approve || Client')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

<style>
    .custom-shadow {
        box-shadow: 0 4px 6px rgba(0, 0, 0, .3), 0 1px 3px rgba(0, 0, 0, .3);
    }
    .custom-hover-shadow:hover {
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0), 0 4px 6px rgba(0, 0, 0, 0);
        transition: box-shadow 0.3s ease;
    }
    .custom-focus-ring:focus {
        outline: none;
        box-shadow: 0 0 0 1px #fa7011;
        transition: box-shadow 0.3s ease;
    }
</style>

<div class="container mx-auto p-4 sm:p-6 flex items-center justify-center h-full">
    <div class="w-full">
        <form action="{{ url('/client/renew/' . $job_draft_id->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="w-[30rem] mx-auto h-80 space-y-4 bg-gray-200 border-gray-600 shadow-lg flex items-center justify-center flex-col">
                <p class="text-2xl">Would you like to renew?</p>
                <h1 class="font-bold text-[#fa7011] text-2xl">{{$job_draft_id->jobOrder->title}}</h1>
                <div class="text-white">
                    <button type="submit" name="renewable" value="1" class="px-6 py-2 bg-[#fa7011] shadow-lg rounded-sm">
                        Yes
                    </button>
                    <button type="submit" name="renewable" value="0" class="px-6 py-2 bg-gray-400 shadow-lg rounded-sm">
                        No
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection