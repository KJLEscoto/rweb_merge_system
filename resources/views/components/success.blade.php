<div class="flex justify-center">
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 transition-opacity z-[999]" id="successModal">
        <div class="bg-white text-gray-800 px-10 py-4 rounded-lg shadow-lg max-w-sm w-full text-center space-y-4 flex items-center justify-center mx-auto flex-col">
            <img src="{{ asset('/Assets/success.png') }}" alt="">
            <p class="text-lg font-semibold">{{ session('Status') }}</p>
            
            <button onclick="document.getElementById('successModal').remove();" class="px-4 py-2 bg-[#fa7011] text-white w-fit">OK</button>
        </div>
    </div>
</div>
