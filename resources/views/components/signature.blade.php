@if(!Auth::user()->signature)
    <!-- Modal Form (within your Blade component or page) -->
    <form action="{{ url('signature/store') }}" method="POST" id="modalSignatureForm">
        @csrf
        @method('PUT')
        <div id="signatureModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-black opacity-50"></div>
            <!-- Modal Content -->
            <div class="bg-white p-6 rounded z-10 max-w-md mx-auto">
                <h1 class="text-xl font-bold mb-4">Add a Signature</h1>
                <p class="mb-4">No signature found. Please create your signature below.</p>
                
                <!-- New Signature Pad -->
                <div id="newSignaturePadContainer">
                    <canvas id="new-signature-pad" class="w-full" style="height:200px;"></canvas>
                    <!-- Hidden input to store the signature data -->
                    <input type="hidden" name="new_signature_pad" id="savedSignaturePadData" value="">
                    <button type="submit" id="saveNewSignature" class="mt-2 px-4 py-2 bg-green-500 text-white rounded">
                        Save and Use Signature
                    </button>
                </div>
                
                <!-- Close Modal Button -->
                <button type="button" id="closeSignatureModal" class="mt-4 px-4 py-2 bg-red-500 text-white rounded">
                    Close
                </button>
            </div>
        </div>
    </form>

    <!-- Modal Script -->
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('signatureModal');
    const canvas = document.getElementById('new-signature-pad');
    const saveButton = document.getElementById('saveNewSignature');
    const closeButton = document.getElementById('closeSignatureModal');
    const hiddenInput = document.getElementById('savedSignaturePadData');
    
    // Initialize the signature pad for the modal canvas
    const signaturePad = new SignaturePad(canvas);

    // Resize canvas function
    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = 200 * ratio; // Fixed height
        canvas.getContext("2d").scale(ratio, ratio);
        signaturePad.clear();
    }

    // Show modal and resize canvas
    document.getElementById("useSavedSignature").addEventListener("click", function () {
        const userSignature = "{{ Auth::user()->signature }}";

        if (!userSignature) {
            // Show modal and resize canvas when it's displayed
            modal.classList.remove('hidden');
            setTimeout(resizeCanvas, 100); // Delay to allow modal rendering
        }
    });

    // Save button: if pad not empty, set hidden input (the form will submit)
    saveButton.addEventListener('click', function(e) {
        if(signaturePad.isEmpty()){
            alert("Please provide a signature first.");
            e.preventDefault(); // Prevent submission if empty
        } else {
            // Set the hidden input value to the signature data URL
            hiddenInput.value = signaturePad.toDataURL("image/png");
            modal.classList.add('hidden'); // Optionally, close the modal after saving
        }
    });

    // Close button simply hides the modal
    closeButton.addEventListener('click', function(){
        modal.classList.add('hidden');
    });

    // Resize canvas on window resize
    window.addEventListener('resize', resizeCanvas);
});

    </script>
@endif
