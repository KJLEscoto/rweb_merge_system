@extends('layouts.application')

@section('title', 'Job Order')
@section('header', 'Job Order')

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

    canvas {
        border: 2px solid #000;
        background-color: #fff;
    }
</style>

<!-- Signature Pad and jQuery -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="mx-auto max-w-screen-2xl">
    <div class="h-full mx-auto max-w-screen-xl">
        {{-- Middle Part --}}
        <div>
            <div>
                <div class="h-auto gap-8 m-10 p-10 relative bg-white" style="box-shadow: 0 20px 30px -5px rgba(0, 0, 0, 0.3); border-radius: 8px;">
                    <div class="rounded-md text-white flex justify-end mb-10">
                        <a href="{{ url('/topmanager') }}"
                           class="w-fit px-4 py-1 bg-[#fa7011] rounded hover:bg-[#d95f0a] transition duration-200">
                            Back
                        </a>
                    </div>

                    <!-- Grid Layout for Details -->
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                        <!-- Project Name -->
                        <div class="lg:col-span-1 font-semibold">Project Name:</div>
                        <div class="lg:col-span-4">{{ $job_draft->jobOrder->title }}</div>

                        <!-- Designation -->
                        <div class="lg:col-span-1 font-semibold">Designation:</div>
                        <div class="lg:col-span-4">{{ Str::title(str_replace('_', ' ', $job_draft->type)) }}</div>

                        <!-- Google Drive Link -->
                        <div class="lg:col-span-1 font-semibold">Google Drive Link:</div>
                        <div class="lg:col-span-4">
                            <div id="draftContent" class="max-h-[300px] rounded-lg overflow-y-auto break-all">
                                {!! $job_draft->draft !!}
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="lg:col-span-1 font-semibold">Date:</div>
                        <div class="lg:col-span-4">{{ $job_draft->date_target }}</div>

                        <!-- Client Name -->
                        <div class="lg:col-span-1 font-semibold">Client Name:</div>
                        <div class="lg:col-span-4">{{ $job_draft->client->name }}</div>

                        <!-- Signature Approval Section -->
                        <div class="hidden lg:block lg:col-span-4"></div>
                        <div class="col-span-1 lg:col-span-1">
                            @if(!$job_draft->signature_top_manager)
                                <div class="mt-6  rounded-md w-fit">
                                    <form action="{{ url('/topmanager/update/' . $job_draft->id) }}" method="POST"
                                          enctype="multipart/form-data" id="approvalForm">
                                        @csrf
                                        @method('PUT')

                                        @php
                                            $isDisabled = $job_draft->status != "Submitted to Top Manager";
                                        @endphp

                                        <!--
                                            If you wish to add a signature method (file upload or pad)
                                            similar to your first code, you can insert that section here.
                                            For now, we’re only including the approval buttons.
                                        -->

                                        <!-- Approve and Decline Buttons -->
                                        <div class="mt-4 flex space-x-4">
                                            <button type="submit"
                                                    class="px-4 py-2 text-sm text-white bg-orange-500 rounded hover:bg-orange-600 disabled:opacity-50 disabled:cursor-not-allowed"
                                                    id="submitBtn" {{ $isDisabled ? 'disabled' : '' }}>
                                                Approve
                                            </button>

                                            <button type="button"
                                                class="px-4 py-2 text-sm text-white bg-red-500 rounded hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed"
                                                id="declineBtn" {{ $isDisabled ? 'disabled' : '' }} onclick="openDeclineModal()">
                                                Decline
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- End Grid Layout -->
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- Decline Modal -->
    <div id="declineModal" class="fixed inset-0 flex items-center justify-center hidden bg-gray-500 bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-md w-[50%]">
            <h2 class="text-xl font-bold mb-4">Decline Job Order</h2>
            <form action="{{ url('/topmanager/decline/' . $job_draft->id) }}" method="POST" id="declineForm">
                @csrf
                <div class="mb-4">
                    <label for="declineReason" class="block text-sm font-semibold mb-2">Reason for Decline:</label>
                    <textarea name="summary" id="declineReason" rows="4" class="w-full border rounded-md p-2" placeholder="Enter your reason..."></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400" onclick="closeDeclineModal()">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Submit Decline</button>
                </div>
            </form>
        </div>
    </div>


    <script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
    <script>
        let declineEditor; // to store the CKEditor instance
    
        function openDeclineModal() {
            // Show the modal
            document.getElementById('declineModal').classList.remove('hidden');
            // Initialize CKEditor if not already done
            if (!declineEditor) {
                ClassicEditor
                    .create(document.querySelector('#declineReason'))
                    .then(editor => {
                        declineEditor = editor;
                    })
                    .catch(error => {
                        console.error('Error initializing CKEditor:', error);
                    });
            }
        }
    
        function closeDeclineModal() {
            document.getElementById('declineModal').classList.add('hidden');
        }
    
        // Ensure the CKEditor content is updated into the textarea before the form is submitted
        document.getElementById("declineForm").addEventListener("submit", function (event) {
            if (declineEditor) {
                document.querySelector('#declineReason').value = declineEditor.getData();
            }
        });
    </script>
{{-- JS for Signature Pad, Live Preview, and Switching (if needed) --}}
<script>
    // Only initialize signature pad if the element exists (in case you add it later)
    var signaturePadElement = document.getElementById("signature-pad");
    if (signaturePadElement) {
        var signaturePad = new SignaturePad(signaturePadElement);

        // Toggle between File Upload and Signature Pad
        var useUploadElement = document.getElementById("useUpload");
        var usePadElement = document.getElementById("usePad");
        if(useUploadElement && usePadElement) {
            useUploadElement.addEventListener("click", function () {
                document.getElementById("uploadSection").classList.remove("hidden");
                document.getElementById("padSection").classList.add("hidden");
                document.getElementById("signaturePadData").value = "";
            });

            usePadElement.addEventListener("click", function () {
                document.getElementById("uploadSection").classList.add("hidden");
                document.getElementById("padSection").classList.remove("hidden");
                document.getElementById("signatureInput").value = "";
            });

            // Clear Signature Pad
            document.getElementById("clearPad").addEventListener("click", function () {
                signaturePad.clear();
            });

            // Convert Signature Pad to Base64 and store it in hidden input before submitting
            document.getElementById("approvalForm").addEventListener("submit", function (event) {
                if (!document.getElementById("uploadSection").classList.contains("hidden")) {
                    return; // Skip if file upload is selected
                }

                if (signaturePad.isEmpty()) {
                    alert("Please sign before submitting.");
                    event.preventDefault();
                } else {
                    document.getElementById("signaturePadData").value = signaturePad.toDataURL("image/png");
                }
            });

            // Image Preview for File Upload
            document.getElementById('signatureInput').addEventListener('change', function (event) {
                const file = event.target.files[0];
                const preview = document.getElementById('imagePreview');

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.src = '#';
                    preview.classList.add('hidden');
                }
            });

            // Enable Submit Button only if checkbox is checked
            document.getElementById('agree').addEventListener('change', function () {
                document.getElementById('submitBtn').disabled = !this.checked;
            });
        }
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const element = document.getElementById('draftContent');
        if (element.scrollHeight > element.clientHeight || element.scrollWidth > element.clientWidth) {
            element.classList.add('border', 'border-gray-200', 'p-4');
        }
    });
</script>
@endsection
