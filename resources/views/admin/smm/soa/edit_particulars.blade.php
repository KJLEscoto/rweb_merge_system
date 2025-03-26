<head>
    <title>{{ env('APP_NAME') }} | SMM | Edit SOA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .custom-shadow {
            box-shadow: 0 2px 4px rgba(0, 0, 0, .3), 0 1px 3px rgba(0, 0, 0, .3);
        }

        .custom-hover-shadow:hover {
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0), 0 4px 6px rgba(0, 0, 0, 0);
            transition: box-shadow 0.3s ease;
        }

        .custom-focus-ring:focus {
            outline: none;
            box-shadow: 0 0 0 1px #545454;
            transition: box-shadow 0.3s ease;
        }

        .ck-editor__editable {
            max-height: 500px !important;
            overflow-y: auto !important;
        }
    </style>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
</head>

<x-main-layout breadcumb="SMM / Direct Job Order" page="Edit SOA">
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
                <div class="w-fit px-4 py-1 bg-gray-400 rounded-md text-white custom-shadow custom-hover-shadow">Back
                </div>
            </a>
        </div>
        <form action="{{ route('admin.smm.soa.update_particulars', $soa->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <h1 class="text-xl font-bold mt-4">Edit SOA {{$soa->job_draft_id}}</h1>
            <div class="grid grid-cols-4 space-y-4">
                <div class="col-span-4 grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <p class="text-sm text-gray-600">Billing Date</p>
                        <input type="date" name="billing_date" value="{{ old('billing_date', $soa->billing_date) }}"
                            class="w-full border px-3 py-2 border-gray-200 rounded-lg">
                        @error('billing_date')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Due Date</p>
                        <input type="date" name="due_date" value="{{ old('due_date', $soa->due_date) }}"
                            class="w-full border px-3 py-2 border-gray-200 rounded-lg">
                        @error('due_date')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-col">
                    <button type="button" onclick="openParticularsModal()"
                        class="bg-blue-500 text-white px-4 py-2 rounded-md mt-4">Add Particulars</button>
                </div>
            </div>

            <div id="particulars-list" class="mt-4">
                @foreach ($soa->particulars as $index => $particular)
                    <div class="p-2 border rounded-md mt-2" id="particular-{{ $particular->id }}">
                        <p><strong>Date:</strong> {{ $particular->date }} | <strong>Reference:</strong> {{ $particular->reference }} | 
                        <strong>Quantity:</strong> {{ $particular->quantity }} | <strong>Particulars:</strong> {{ $particular->particulars }} | 
                        <strong>Charges:</strong> {{ $particular->charges }}</p>

                        <!-- Edit and Delete Buttons -->
                        <button type="button" onclick="editParticular({{ $particular->id }})" class="bg-yellow-500 text-white px-4 py-2 rounded-md">Edit</button>
                        <button type="button" onclick="deleteParticular({{ $particular->id }})" class="bg-red-500 text-white px-4 py-2 rounded-md">Delete</button>

                        <!-- Hidden fields for form submission -->
                        <input type="hidden" name="particulars[{{ $index }}][id]" value="{{ $particular->id }}">
                        <input type="hidden" name="particulars[{{ $index }}][date]" value="{{ $particular->date }}">
                        <input type="hidden" name="particulars[{{ $index }}][reference]" value="{{ $particular->reference }}">
                        <input type="hidden" name="particulars[{{ $index }}][quantity]" value="{{ $particular->quantity }}">
                        <input type="hidden" name="particulars[{{ $index }}][particulars]" value="{{ $particular->particulars }}">
                        <input type="hidden" name="particulars[{{ $index }}][charges]" value="{{ $particular->charges }}">
                    </div>
                @endforeach
            </div>

            <button type="submit"
                class="col-span-1 text-center py-2 lg:py-4 w-full bg-[#fa7011] mt-10 rounded-lg custom-shadow custom-hover-shadow text-white font-bold">Update</button>
        </form>
    </div>

    <div id="particulars-modal" class="hidden z-50 fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg w-1/3">
            <h2 class="text-lg font-bold mb-4">Add Particular</h2>
            <label>Date:</label>
            <input type="date" id="particular-date" class="w-full border p-2 rounded-lg mb-2">
            <label>Reference:</label>
            <input type="text" id="particular-reference" value="{{ $soa->job_draft_id }}" class="w-full border p-2 rounded-lg mb-2" readonly>
                    
            <label>Quantity:</label>
            <input type="number" id="particular-quantity" class="w-full border p-2 rounded-lg mb-2">
            <label>Particulars:</label>
            <input type="text" id="particular-particulars" class="w-full border p-2 rounded-lg mb-2">
            <label>Charges:</label>
            <input type="number" id="particular-charges" class="w-full border p-2 rounded-lg mb-2">
            <div class="flex justify-end gap-2 mt-4">
                <button onclick="closeParticularsModal()"
                    class="bg-gray-500 text-white px-4 py-2 rounded-md">Cancel</button>
                <button onclick="addParticular()" class="bg-blue-500 text-white px-4 py-2 rounded-md">Add</button>
            </div>
        </div>
    </div>
</x-main-layout>

<script>
function openParticularsModal() {
    // Reset modal for adding new particular
    document.getElementById('particular-date').value = '';
    document.getElementById('particular-quantity').value = '';
    document.getElementById('particular-particulars').value = '';
    document.getElementById('particular-charges').value = '';

    // Reset modal title
    document.querySelector('#particulars-modal h2').textContent = 'Add Particular';

    // Show add button, hide save button
    document.querySelector('#particulars-modal button.bg-blue-500').style.display = 'block';
    const existingEditButton = document.querySelector('#particulars-modal button[data-edit-id]');
    if (existingEditButton) {
        existingEditButton.remove();
    }

    document.getElementById('particulars-modal').classList.remove('hidden');
}

    function closeParticularsModal() {
        document.getElementById('particulars-modal').classList.add('hidden');
    }

    let particularsCount = {{ count($soa->particulars) }};
    let particularsTracker = {};

    // Initialize tracker for existing particulars
    @foreach ($soa->particulars as $index => $particular)
        particularsTracker[{{ $particular->id }}] = {{ $index }};
    @endforeach


    function addParticular() {
    const date = document.getElementById('particular-date').value;
    const reference = "{{ $soa->id }}"; // Always use SOA ID
    const quantity = document.getElementById('particular-quantity').value;
    const particulars = document.getElementById('particular-particulars').value;
    const charges = document.getElementById('particular-charges').value;

    if (date && reference && quantity && particulars && charges) {
        const particularsList = document.getElementById('particulars-list');

        // Create a unique identifier (use a large random number to avoid conflicts)
        const uniqueId = Math.floor(Math.random() * 1000000);

        // Create a wrapper div for each particular
        const div = document.createElement('div');
        div.classList.add('p-2', 'border', 'rounded-md', 'mt-2');
        div.id = `particular-${uniqueId}`;

        // Include edit and delete buttons in the HTML
        div.innerHTML = `
            <p><strong>Date:</strong> ${date} | <strong>Reference:</strong> ${reference} | 
            <strong>Quantity:</strong> ${quantity} | <strong>Particulars:</strong> ${particulars} | <strong>Charges:</strong> ${charges}</p>

            <!-- Edit and Delete Buttons -->
            <button type="button" onclick="editParticular(${uniqueId})" class="bg-yellow-500 text-white px-4 py-2 rounded-md">Edit</button>
            <button type="button" onclick="deleteParticular(${uniqueId})" class="bg-red-500 text-white px-4 py-2 rounded-md">Delete</button>

            <!-- Hidden fields for form submission -->
            <input type="hidden" name="particulars[${particularsCount}][unique_id]" value="${uniqueId}">
            <input type="hidden" name="particulars[${particularsCount}][date]" value="${date}">
            <input type="hidden" name="particulars[${particularsCount}][reference]" value="${reference}">
            <input type="hidden" name="particulars[${particularsCount}][quantity]" value="${quantity}">
            <input type="hidden" name="particulars[${particularsCount}][particulars]" value="${particulars}">
            <input type="hidden" name="particulars[${particularsCount}][charges]" value="${charges}">
        `;

        // Track this particular
        particularsTracker[uniqueId] = particularsCount;

        particularsList.appendChild(div);
        particularsCount++; // Increment count for next item

        closeParticularsModal();
    } else {
        alert('Please fill in all fields');
    }
}

function editParticular(uniqueId) {
    // Find the particular div
    const particularDiv = document.getElementById(`particular-${uniqueId}`);
    
    // Open the modal
    document.getElementById('particulars-modal').classList.remove('hidden');
    
    // Find the correct index for this particular
    const index = particularsTracker[uniqueId];

    // Populate modal fields with existing data
    const existingDate = particularDiv.querySelector(`input[name$="[date]"]`).value;
    const existingReference = particularDiv.querySelector(`input[name$="[reference]"]`).value;
    const existingQuantity = particularDiv.querySelector(`input[name$="[quantity]"]`).value;
    const existingParticulars = particularDiv.querySelector(`input[name$="[particulars]"]`).value;
    const existingCharges = particularDiv.querySelector(`input[name$="[charges]"]`).value;

    // Set input values
    document.getElementById('particular-date').value = existingDate;
    document.getElementById('particular-reference').value = existingReference;
    document.getElementById('particular-quantity').value = existingQuantity;
    document.getElementById('particular-particulars').value = existingParticulars;
    document.getElementById('particular-charges').value = existingCharges;

    // Change modal title
    const modalTitle = document.querySelector('#particulars-modal h2');
    modalTitle.textContent = 'Edit Particular';

    // Remove any existing edit-save button
    const existingEditButton = document.querySelector('#particulars-modal button[data-edit-id]');
    if (existingEditButton) {
        existingEditButton.remove();
    }

    // Create a new edit-save button
    const addButton = document.querySelector('#particulars-modal button.bg-blue-500');
    const editSaveButton = document.createElement('button');
    editSaveButton.textContent = 'Save Changes';
    editSaveButton.classList.add('bg-green-500', 'text-white', 'px-4', 'py-2', 'rounded-md');
    editSaveButton.setAttribute('data-edit-id', uniqueId);
    editSaveButton.onclick = function() {
        // Get updated values
        const updatedDate = document.getElementById('particular-date').value;
        const updatedReference = document.getElementById('particular-reference').value;
        const updatedQuantity = document.getElementById('particular-quantity').value;
        const updatedParticulars = document.getElementById('particular-particulars').value;
        const updatedCharges = document.getElementById('particular-charges').value;

        if (updatedDate && updatedReference && updatedQuantity && updatedParticulars && updatedCharges) {
            // Update the displayed text
            particularDiv.querySelector('p').innerHTML = 
                `<strong>Date:</strong> ${updatedDate} | <strong>Reference:</strong> ${updatedReference} | 
                <strong>Quantity:</strong> ${updatedQuantity} | <strong>Particulars:</strong> ${updatedParticulars} | 
                <strong>Charges:</strong> ${updatedCharges}`;

            // Update hidden input values
            const inputs = particularDiv.querySelectorAll('input[type="hidden"]');
            inputs.forEach(input => {
                if (input.name.includes('[date]')) input.value = updatedDate;
                if (input.name.includes('[reference]')) input.value = updatedReference;
                if (input.name.includes('[quantity]')) input.value = updatedQuantity;
                if (input.name.includes('[particulars]')) input.value = updatedParticulars;
                if (input.name.includes('[charges]')) input.value = updatedCharges;
            });

            // Reset modal
            closeParticularsModal();
        } else {
            alert('Please fill in all fields');
        }
    };

    // Insert the new edit-save button before the cancel button
    const cancelButton = document.querySelector('#particulars-modal button.bg-gray-500');
    cancelButton.parentNode.insertBefore(editSaveButton, cancelButton);

    // Remove the add button
    addButton.style.display = 'none';
}

function closeParticularsModal() {
    // Reset all input fields
    document.getElementById('particular-date').value = '';
    document.getElementById('particular-quantity').value = '';
    document.getElementById('particular-particulars').value = '';
    document.getElementById('particular-charges').value = '';

    // Hide the modal
    document.getElementById('particulars-modal').classList.add('hidden');

    // Reset modal title
    const modalTitle = document.querySelector('#particulars-modal h2');
    modalTitle.textContent = 'Add Particular';

    // Remove edit-save button if exists
    const editSaveButton = document.querySelector('#particulars-modal button[data-edit-id]');
    if (editSaveButton) {
        editSaveButton.remove();
    }

    // Show the add button
    const addButton = document.querySelector('#particulars-modal button.bg-blue-500');
    addButton.style.display = 'block';
}

function deleteParticular(uniqueId) {
    const particularDiv = document.getElementById(`particular-${uniqueId}`);
    
    if (confirm('Are you sure you want to delete this particular?')) {
        // Remove the particular div
        particularDiv.remove();
        
        // Remove the tracker for this particular
        delete particularsTracker[uniqueId];
    }
}
</script>
