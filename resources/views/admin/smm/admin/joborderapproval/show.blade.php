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

    <!-- Signature Pad Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <div class="h-auto gap-8 m-4 lg:m-10 p-4 lg:p-10 relative bg-white"
        style="box-shadow: 0 20px 30px -5px rgba(0, 0, 0, 0.3); border-radius: 8px;">
        <div class="rounded-md text-white flex justify-end mb-10">
            <a href="{{ url('/operation') }}"
                class="w-fit px-4 py-1 bg-[#fa7011] rounded hover:bg-[#d95f0a] transition duration-200">
                Back
            </a>
        </div>

        <!-- Responsive Grid for Details -->
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

            <!-- Signature Upload Section (Full Width) -->
            <div class="hidden lg:block lg:col-span-1"></div>
            <div class="lg:col-span-4">
                @php
                    $isDisabled = $job_draft->status != "Submitted to Operations";
                    $isSigned = !empty($job_draft->signature_admin);
                @endphp

                <div class="mt-6 bg-white p-4 rounded-md shadow-md w-fit">
                    <div class="flex justify-between">
                        <h1 class="text-sm font-semibold">Choose Signature Method:</h1>
                        <div class="flex space-x-2">
                            <button id="useUpload"
                                class="px-2 border rounded {{ Auth::user()->signature ? '' : 'bg-gray-200' }}" {{ $isSigned ? 'disabled' : '' }}>
                                <i class="fa-solid fa-file-arrow-up" style="color: #fa7011;"></i>
                            </button>
                            <button id="usePad" class="px-2 border rounded" {{ $isSigned ? 'disabled' : '' }}>
                                <i class="fa-solid fa-file-signature" style="color: #fa7011;"></i>
                            </button>
                            <button id="useSavedSignature"
                                class="px-2 border rounded {{ Auth::user()->signature ? 'bg-gray-200' : '' }}" {{ $isSigned ? 'disabled' : '' }}>
                                <i class="fa-solid fa-cloud-arrow-up" style="color: #fa7011;"></i>
                            </button>
                        </div>
                    </div>

                    <form action="{{ url('/operation/update/' . $job_draft->id) }}" method="POST"
                        enctype="multipart/form-data" id="approvalForm">
                        @csrf
                        @method('PUT')

                        {{-- File Upload --}}
                        <div id="uploadSection" class="{{ Auth::user()->signature ? 'hidden' : '' }}">
                            <input type="file" name="signature_admin" accept="image/*"
                                class="mt-2 border p-2 w-full rounded-md" id="signatureInput" {{ $isDisabled || $isSigned ? 'disabled' : '' }}>
                            <div
                                class="mt-4 w-52 h-32 border border-gray-300 rounded-md overflow-hidden flex items-center justify-center bg-gray-100">
                                <img id="imagePreview" src="{{ $isSigned ? asset($job_draft->signature_admin) : '' }}"
                                    alt="Selected Image"
                                    class="{{ $isSigned ? 'block' : 'hidden' }} w-full h-full object-cover">
                            </div>
                        </div>

                        {{-- Signature Pad --}}
                        <div id="padSection" class="hidden">
                            <canvas id="signature-pad" class="w-[300px] lg:w-[400px]"
                                style="height:200px; {{ $isSigned ? 'pointer-events:none;opacity:0.5;' : '' }}"></canvas>
                            <div class="mt-2 flex">
                                <button type="button" id="clearPad" class="bg-gray-500 text-white px-2 py-1 rounded mr-2" {{ $isSigned ? 'disabled' : '' }}>
                                    Clear
                                </button>
                            </div>
                            <input type="hidden" name="signature_pad" id="signaturePadData"
                                value="{{ old('signature_pad') }}">
                        </div>

                        {{-- Saved Signature Section --}}
                        <div id="savedPadSection" class="{{ Auth::user()->signature ? '' : 'hidden' }}">
                            @if (Auth::user()->signature)
                            <img id="new-signature-pad-main" class="w-[300px] lg:w-[400px]" style="height:200px;"
                            src="{{ asset(Auth::user()->signature) }}" alt="Your Saved Signature">
                            @endif
                            <div class="mt-2 flex">
                                <input type="hidden" name="new_signature_pad" id="savedSignatureData"
                                    value="{{ asset(Auth::user()->signature) }}">
                            </div>
                        </div>

                        @if($errors->has('signature_admin') || $errors->has('signature_pad'))
                            <p class="text-sm text-red-600">
                                {{ $errors->first('signature_admin') ?: $errors->first('signature_pad') }}
                            </p>
                        @endif

                        {{-- Agreement Checkbox --}}
                        <div class="mt-4 flex items-center space-x-2">
                            <input type="checkbox" id="agree" required {{ $isSigned ? 'disabled' : '' }}>
                            <label for="agree">I agree to the terms and conditions.</label>
                        </div>

                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <p class="text-red-600">{{$error}}</p>
                            @endforeach
                        @endif

                        {{-- Approve and Decline Buttons --}}
                        <div class="mt-4 flex space-x-4">
                            <button type="submit"
                                class="px-4 py-2 text-sm text-white bg-orange-500 rounded hover:bg-orange-600 disabled:opacity-50 disabled:cursor-not-allowed"
                                id="submitBtn" {{ $isSigned ? 'disabled' : '' }}>
                                Submit Approval
                            </button>

                            <button type="button"
                                class="px-4 py-2 text-sm text-white bg-red-500 rounded hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed"
                                id="declineBtn" {{ $isSigned ? 'disabled' : '' }} onclick="openDeclineModal()">
                                Decline
                            </button>
                        

                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
    </div>
    </div>
    </div>
    </div>



    <!-- Decline Modal -->
    <div id="declineModal" class="fixed inset-0 flex items-center justify-center hidden bg-gray-500 bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-md w-[50%]">
            <h2 class="text-xl font-bold mb-4">Decline Job Order</h2>
            <form action="{{ url('/operation/decline/' . $job_draft->id) }}" method="POST" id="declineForm">
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


    {{-- Include the signature modal (hidden by default) --}}
    <x-signature id="signatureModal" class="hidden" />

    {{-- JS for Live Preview, Signature Pad, and Modal Switching --}}
    <script>
    // Main Signature Pad
    var canvas = document.getElementById("signature-pad");
    var signaturePad = new SignaturePad(canvas);

    // Resize canvas for better drawing experience
    function resizeCanvas() {
        var ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
        signaturePad.clear(); // Clear canvas after resizing
    }
    window.addEventListener("resize", resizeCanvas);
    resizeCanvas(); // Call on page load

    // Signature Method Buttons
    document.getElementById("useUpload").addEventListener("click", function () {
        toggleSection("upload");
    });
    document.getElementById("usePad").addEventListener("click", function () {
        toggleSection("pad");
    });
    document.getElementById("useSavedSignature").addEventListener("click", function () {
        const userSignature = "{{ Auth::user()->signature }}";
        if (!userSignature) {
            document.getElementById("signatureModal").classList.remove("hidden");
        } else {
            // Use "savedSignature" to match the button id in the toggle logic
            toggleSection("savedSignature");
        }
    });

    function toggleSection(method) {
        const sections = ["uploadSection", "padSection", "savedPadSection"];
        sections.forEach(section => document.getElementById(section).classList.add("hidden"));

        if (method === "upload") {
            document.getElementById("uploadSection").classList.remove("hidden");
        } else if (method === "pad") {
            document.getElementById("padSection").classList.remove("hidden");
            setTimeout(() => resizeCanvas(), 100);
        } else if (method === "savedSignature") {
            document.getElementById("savedPadSection").classList.remove("hidden");
        }

        // Highlight the selected button
        ["useUpload", "usePad", "useSavedSignature"].forEach(btn => {
            document.getElementById(btn).classList.remove("bg-gray-200");
        });
        if (method === "upload") {
            document.getElementById("useUpload").classList.add("bg-gray-200");
        } else if (method === "pad") {
            document.getElementById("usePad").classList.add("bg-gray-200");
        } else if (method === "savedSignature") {
            document.getElementById("useSavedSignature").classList.add("bg-gray-200");
        }

        // Reset hidden inputs
        document.getElementById("signaturePadData").value = "";
        document.getElementById("savedSignatureData").value = method === "savedSignature" ? "{{ asset(Auth::user()->signature) }}" : "";
    }

    // Signature Pad Clear
    document.getElementById("clearPad").addEventListener("click", function () {
        signaturePad.clear();
    });

    // Live Preview for File Upload
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

    // Enable Submit Button on Agreement
    document.getElementById('agree').addEventListener('change', function () {
        document.getElementById('submitBtn').disabled = !this.checked;
    });

    // Form Submission - Correctly capture signaturePad value
    document.getElementById("approvalForm").addEventListener("submit", function (event) {
        // Capture Signature Pad data before submitting
        if (!document.getElementById("uploadSection").classList.contains("hidden")) {
            if (!document.getElementById("signatureInput").value) {
                alert("Please upload an image before submitting.");
                event.preventDefault();
                return;
            }
        } else if (!document.getElementById("padSection").classList.contains("hidden")) {
            if (!signaturePad || signaturePad.isEmpty()) {
                alert("Please sign before submitting.");
                event.preventDefault();
                return;
            } else {
                // Set signaturePad value into hidden input
                const signatureData = signaturePad.toDataURL("image/png");
                document.getElementById("signaturePadData").value = signatureData;
            }
        } else if (!document.getElementById("savedPadSection").classList.contains("hidden")) {
            if (!document.getElementById("savedSignatureData").value) {
                alert("Please select your saved signature before submitting.");
                event.preventDefault();
                return;
            }
        }
    });
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
