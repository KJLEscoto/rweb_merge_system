<head>
    <title>{{ env('APP_NAME') }} | SMM | Create SOA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .custom-shadow { box-shadow: 0 2px 4px rgba(0, 0, 0, .3), 0 1px 3px rgba(0, 0, 0, .3); }
        .custom-hover-shadow:hover { box-shadow: 0 10px 15px rgba(0, 0, 0, 0), 0 4px 6px rgba(0, 0, 0, 0); transition: box-shadow 0.3s ease; }
        .custom-focus-ring:focus { outline: none; box-shadow: 0 0 0 1px #545454; transition: box-shadow 0.3s ease; }
        .ck-editor__editable { max-height: 500px !important; overflow-y: auto !important; }
    </style>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
</head>

<x-main-layout breadcumb="SMM / Direct Job Order" page="Create SOA">
    @if (!Auth::user()->signature)
        <form action="{{ url('signature/store') }}" method="POST" id="modalSignatureForm">
            @csrf
            @method('PUT')
            <x-save-signature />
        </form>
    @endif
    
    <div class="w-full px-6 py-10 mx-auto rounded-lg custom-shadow bg-white">
        <div class="w-fit">
            <a href="{{ route('admin.smm.soa') }}">
                <div class="w-fit px-4 py-1 bg-gray-400 rounded-md text-white custom-shadow custom-hover-shadow">Back</div>
            </a>
        </div>
        <form action="{{ route('admin.smm.soa.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <h1 class="text-xl font-bold mt-4">Create SOA</h1>
            <div class="grid grid-cols-4 space-y-4">
                <div class="col-span-4 grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <p class="text-sm text-gray-600">Billing Date</p>
                        <input type="date" name="billing_date" value="{{ old('billing_date') }}" class="w-full border px-3 py-2 border-gray-200 rounded-lg">
                        @error('billing_date')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Due Date</p>
                        <input type="date" name="due_date" value="{{ old('due_date') }}" class="w-full border px-3 py-2 border-gray-200 rounded-lg">
                        @error('due_date')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="flex flex-col">
                    <button type="button" onclick="openParticularsModal()" class="bg-blue-500 text-white px-4 py-2 rounded-md mt-4">Add Particulars</button>
                </div>
            </div>
            <div id="particulars-list" class="mt-4"></div>
            <button type="submit" class="col-span-1 text-center py-2 lg:py-4 w-full bg-[#fa7011] mt-10 rounded-lg custom-shadow custom-hover-shadow text-white font-bold">Submit</button>
        </form>
    </div>

    <div id="particulars-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg w-1/3">
            <h2 class="text-lg font-bold mb-4">Add Particular</h2>
            <label>Date:</label>
            <input type="date" id="particular-date" class="w-full border p-2 rounded-lg mb-2">
            <label>Reference:</label>
            <input type="text" id="particular-reference" class="w-full border p-2 rounded-lg mb-2">
            <label>Quantity:</label>
            <input type="number" id="particular-qty" class="w-full border p-2 rounded-lg mb-2">
            <label>Particulars:</label>
            <input type="text" id="particular-description" class="w-full border p-2 rounded-lg mb-2">
            <label>Charges:</label>
            <input type="number" id="particular-charges" class="w-full border p-2 rounded-lg mb-2">
            <div class="flex justify-end gap-2 mt-4">
                <button onclick="closeParticularsModal()" class="bg-gray-500 text-white px-4 py-2 rounded-md">Cancel</button>
                <button onclick="addParticular()" class="bg-blue-500 text-white px-4 py-2 rounded-md">Add</button>
            </div>
        </div>
    </div>
</x-main-layout>

<script>
    function openParticularsModal() { document.getElementById('particulars-modal').classList.remove('hidden'); }
    function closeParticularsModal() { document.getElementById('particulars-modal').classList.add('hidden'); }
    function addParticular() {
        const date = document.getElementById('particular-date').value;
        const reference = document.getElementById('particular-reference').value;
        const qty = document.getElementById('particular-qty').value;
        const description = document.getElementById('particular-description').value;
        const charges = document.getElementById('particular-charges').value;
        
        if (date && reference && qty && description && charges) {
            const particularsList = document.getElementById('particulars-list');
            const div = document.createElement('div');
            div.classList.add('p-2', 'border', 'rounded-md', 'mt-2');
            div.innerHTML = `<p><strong>Date:</strong> ${date} | <strong>Reference:</strong> ${reference} | <strong>Qty:</strong> ${qty} | <strong>Particulars:</strong> ${description} | <strong>Charges:</strong> ${charges}</p>`;
            particularsList.appendChild(div);
            closeParticularsModal();
        } else {
            alert('Please fill in all fields');
        }
    }
</script>