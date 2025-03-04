<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#fa7011] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#fa5d11] focus:bg-[#fa5d11] active:bg-[#fa4911] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
