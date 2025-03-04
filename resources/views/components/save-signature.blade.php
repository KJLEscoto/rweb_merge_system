<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<div id="signatureModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black opacity-50"></div>
    <!-- Modal Content -->
    <div class="bg-white p-6 rounded z-10 max-w-md mx-auto">
        <h1 class="text-xl font-bold mb-4">Add a Signature</h1>
        <p class="mb-4">No signature found. Please create or upload your signature below.</p>
        
        <!-- Toggle Buttons for Drawing or Uploading -->
        <div class="flex mb-4">
            <button type="button" id="drawSignatureToggle" class="mr-2 px-4 py-2 bg-blue-500 text-white rounded">Draw Signature</button>
            <button type="button" id="uploadSignatureToggle" class="px-4 py-2 bg-blue-500 text-white rounded">Upload Signature</button>
        </div>
        
        <!-- Signature Pad Container -->
        <div id="newSignaturePadContainer">
            <canvas id="new-signature-pad" class="w-full border border-gray-400" style="height:200px;"></canvas>
            <!-- Hidden input to store the signature data -->
            <input type="hidden" name="new_signature_pad" id="savedSignaturePadData" value="">
        </div>

        <!-- Upload Signature Container (hidden by default) -->
        <div id="uploadSignatureContainer" class="hidden">
            <input type="file" id="uploadSignatureInput" accept="image/*">
            <img id="uploadedSignaturePreview" src="" alt="Uploaded Signature" class="mt-2 w-full border border-gray-400" style="display:none; height:200px;">
        </div>
        
        <button type="submit" id="saveNewSignature" class="mt-4 px-4 py-2 bg-green-500 text-white rounded">
            Save and Use Signature
        </button>
        
        <!-- Optional Close Modal Button -->
        <!-- <button type="button" id="closeSignatureModal" class="mt-4 px-4 py-2 bg-red-500 text-white rounded">Close</button> -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('signatureModal');
    // Immediately show the modal
    modal.classList.remove('hidden');

    // Elements for toggling between draw and upload modes
    const drawToggle = document.getElementById('drawSignatureToggle');
    const uploadToggle = document.getElementById('uploadSignatureToggle');
    const signaturePadContainer = document.getElementById('newSignaturePadContainer');
    const uploadContainer = document.getElementById('uploadSignatureContainer');

    // Default mode: Draw signature
    signaturePadContainer.classList.remove('hidden');
    uploadContainer.classList.add('hidden');

    // Toggle to Draw Signature mode
    drawToggle.addEventListener('click', function() {
        signaturePadContainer.classList.remove('hidden');
        uploadContainer.classList.add('hidden');
    });

    // Toggle to Upload Signature mode
    uploadToggle.addEventListener('click', function() {
        uploadContainer.classList.remove('hidden');
        signaturePadContainer.classList.add('hidden');
    });

    // Initialize Signature Pad for drawing
    const canvas = document.getElementById('new-signature-pad');
    const signaturePad = new SignaturePad(canvas);
    const saveButton = document.getElementById('saveNewSignature');
    const hiddenInput = document.getElementById('savedSignaturePadData');

    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = 200 * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
        signaturePad.clear();
    }
    // Delay resize to ensure the modal is rendered
    setTimeout(resizeCanvas, 100);
    
    // Handle file upload for signature image
    const uploadInput = document.getElementById('uploadSignatureInput');
    const uploadPreview = document.getElementById('uploadedSignaturePreview');

    uploadInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                uploadPreview.src = e.target.result;
                uploadPreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });

    // Save button event
    saveButton.addEventListener('click', function(e) {
        // If in Draw mode, use signature pad data
        if (!signaturePadContainer.classList.contains('hidden')) {
            if(signaturePad.isEmpty()){
                alert("Please provide a signature first.");
                e.preventDefault();
                return;
            } else {
                hiddenInput.value = signaturePad.toDataURL("image/png");
            }
        } else { // Upload mode
            if (!uploadInput.files[0]) {
                alert("Please upload a signature image.");
                e.preventDefault();
                return;
            } else {
                // Save the uploaded image's data URL
                hiddenInput.value = uploadPreview.src;
            }
        }
        modal.classList.add('hidden');
    });

    // If you want to add functionality for a close button, uncomment the following:
    // const closeButton = document.getElementById('closeSignatureModal');
    // closeButton.addEventListener('click', function(){
    //     modal.classList.add('hidden');
    // });

    window.addEventListener('resize', resizeCanvas);
});
</script>
