@extends('layouts.application')

@section('title', 'Clients')
@section('header', 'Renew JO')

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

    /* Toggle Switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 34px;
        height: 20px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #fa7011;
    }

    input:checked + .slider:before {
        transform: translateX(14px);
    }
</style>

<div class="container mx-auto p-6">
    <div class="overflow-x-auto overflow-y-auto bg-white shadow-md rounded-lg h-[500px]" style="max-height: 500px;">
        <table class="w-full text-left border-collapse" id="projectTable">
            <thead class="sticky top-0 z-5 bg-[#fa7011] text-white">
                <tr>
                    <th class="px-6 py-3">Title</th>
                    <th class="px-6 py-3">Renewable</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse ($job_orders as $job_order)
                    <tr class="project-row border-b">
                        <td class="px-6 py-3">{{ $job_order->title }}</td>

                        <td class="px-6 py-3 border-b">
                            <label class="switch">
                                <input type="checkbox" class="renew-toggle" data-id="{{ $job_order->id }}" {{ $job_order->renewable ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </td>
                    </tr>
                @empty
                    <tr class="h-[400px]">
                        <td colspan="3" class="px-6 py-3">
                            <div class="flex h-full items-center flex-col justify-center space-y-4">
                                <i class="far fa-grin-beam-sweat text-7xl" style="color: #fa7011;"></i>
                                <p class="text-[#fa7011]">No Data Found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.renew-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            let jobOrderId = this.getAttribute('data-id');
            let newStatus = this.checked ? 1 : 0;

            fetch(`/operation/update/${jobOrderId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ renewable: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert('Failed to update status');
                    this.checked = !this.checked; // Revert toggle if failed
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});

</script>

@endsection
