<head>
    <title>{{ env('APP_NAME') }} | SMM | Create SOA</title>
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
                <div class="w-fit px-4 py-1 bg-gray-400 rounded-md text-white custom-shadow custom-hover-shadow">Back
                </div>
            </a>
        </div>
        <form action="{{ route('admin.smm.soa.store_particulars', $soa->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <h1 class="text-xl font-bold mt-4">Create SOA</h1>
            <div class="grid grid-cols-4 space-y-4">
                <div class="col-span-4 grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <p class="text-sm text-gray-600">Billing Date</p>
                        <input type="date" name="billing_date" value="{{ old('billing_date') }}"
                            class="w-full border px-3 py-2 border-gray-200 rounded-lg">
                        @error('billing_date')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Due Date</p>
                        <input type="date" name="due_date" value="{{ old('due_date') }}"
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
            <div id="particulars-list" class="mt-4"></div>
            <button type="submit"
                class="col-span-1 text-center py-2 lg:py-4 w-full bg-[#fa7011] mt-10 rounded-lg custom-shadow custom-hover-shadow text-white font-bold">Submit</button>
        </form>
    </div>

    <div id="particulars-modal" class="hidden z-50 fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg w-1/3">
            <h2 class="text-lg font-bold mb-4">Add Particular</h2>
            <label>Date:</label>
            <input type="date" id="particular-date" class="w-full border p-2 rounded-lg mb-2">
            <label>Reference:</label>
            <input type="text" id="particular-reference" value="{{$soa->job_draft_id}}" class="w-full border p-2 rounded-lg mb-2" readonly>
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
function openParticularsModal(isEdit = false, index = null) {
    // Reset the modal button and fields for adding
    const addButton = document.querySelector('#particulars-modal button:nth-child(2)');
    addButton.innerText = "Add";  // Set button text to "Add"
    addButton.setAttribute('onclick', `addParticular()`);  // Set the correct function call for adding
    
    if (isEdit) {
        const particular = particularsData[index];
        document.getElementById('particular-date').value = particular.date;
        document.getElementById('particular-reference').value = particular.reference;
        document.getElementById('particular-quantity').value = particular.quantity;
        document.getElementById('particular-particulars').value = particular.particulars;
        document.getElementById('particular-charges').value = particular.charges;

        // Update the button to "Update" for editing
        addButton.innerText = "Update";
        addButton.setAttribute('onclick', `updateParticular(${index})`);
    } else {
        // Clear input fields for adding a new particular
        document.getElementById('particular-date').value = '';
        document.getElementById('particular-quantity').value = '';
        document.getElementById('particular-particulars').value = '';
        document.getElementById('particular-charges').value = '';
    }

    document.getElementById('particulars-modal').classList.remove('hidden');
}
function closeParticularsModal() {
    document.getElementById('particulars-modal').classList.add('hidden');
}

    let particularsIndex = 0; // Track the index for each new particular
    let particularsData = []; // Store the particulars data for editing
    const reference = "{{ $soa->job_draft_id }}"; // Reference should always be this

    function addParticular() {
    const date = document.getElementById('particular-date').value;
    const quantity = document.getElementById('particular-quantity').value;
    const particulars = document.getElementById('particular-particulars').value;
    const charges = document.getElementById('particular-charges').value;

    if (date && quantity && particulars && charges) {
        const particularsList = document.getElementById('particulars-list');

        // Create a wrapper div for each particular
        const div = document.createElement('div');
        div.classList.add('p-2', 'border', 'rounded-md', 'mt-2');
        div.id = `particular-${particularsIndex}`;  // Set a unique ID for each particular
        div.innerHTML = `
            <p><strong>Date:</strong> ${date} | <strong>Reference:</strong> ${reference} | 
            <strong>Quantity:</strong> ${quantity} | <strong>Particulars:</strong> ${particulars} | 
            <strong>Charges:</strong> ${charges}</p>
            <input type="hidden" name="particulars[${particularsIndex}][date]" value="${date}">
            <input type="hidden" name="particulars[${particularsIndex}][reference]" value="${reference}">
            <input type="hidden" name="particulars[${particularsIndex}][quantity]" value="${quantity}">
            <input type="hidden" name="particulars[${particularsIndex}][particulars]" value="${particulars}">
            <input type="hidden" name="particulars[${particularsIndex}][charges]" value="${charges}">
            <button type="button" onclick="editParticular(${particularsIndex})" class="bg-yellow-500 text-white px-4 py-2 rounded-md mt-2 mr-2">Edit</button>
            <button type="button" onclick="deleteParticular(${particularsIndex})" class="bg-red-500 text-white px-4 py-2 rounded-md mt-2">Delete</button>
        `;

        particularsList.appendChild(div);

        // Store the particular's data for future editing
        particularsData.push({
            date,
            reference,
            quantity,
            particulars,
            charges
        });

        particularsIndex++; // Increment index for next item

        closeParticularsModal();
    } else {
        alert('Please fill in all fields');
    }
}

function editParticular(index) {
    // Open the modal for editing and pass the `true` flag for editing
    openParticularsModal(true, index);
}

    function updateParticular(index) {
    const date = document.getElementById('particular-date').value;
    const quantity = document.getElementById('particular-quantity').value;
    const particulars = document.getElementById('particular-particulars').value;
    const charges = document.getElementById('particular-charges').value;

    if (date && quantity && particulars && charges) {
        // Update the particulars data
        particularsData[index] = { date, reference, quantity, particulars, charges };

        // Find the particular's div and update it
        const div = document.getElementById(`particular-${index}`);
        div.innerHTML = `
            <p><strong>Date:</strong> ${date} | <strong>Reference:</strong> ${reference} | 
            <strong>Quantity:</strong> ${quantity} | <strong>Particulars:</strong> ${particulars} | 
            <strong>Charges:</strong> ${charges}</p>
            <input type="hidden" name="particulars[${index}][date]" value="${date}">
            <input type="hidden" name="particulars[${index}][reference]" value="${reference}">
            <input type="hidden" name="particulars[${index}][quantity]" value="${quantity}">
            <input type="hidden" name="particulars[${index}][particulars]" value="${particulars}">
            <input type="hidden" name="particulars[${index}][charges]" value="${charges}">
            <button type="button" onclick="editParticular(${index})" class="bg-yellow-500 text-white px-4 py-2 rounded-md mt-2 mr-2">Edit</button>
            <button type="button" onclick="deleteParticular(${index})" class="bg-red-500 text-white px-4 py-2 rounded-md mt-2">Delete</button>
        `;

        closeParticularsModal();
    } else {
        alert('Please fill in all fields');
    }
}


function deleteParticular(index) {
    const div = document.getElementById(`particular-${index}`);
    div.remove();

    particularsData.splice(index, 1);
}
</script>


