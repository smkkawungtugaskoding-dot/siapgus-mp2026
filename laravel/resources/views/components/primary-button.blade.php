<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full inline-flex justify-center items-center py-2.5 px-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 border border-transparent rounded-xl font-bold text-sm text-white tracking-wide shadow-lg shadow-blue-600/30 transition ease-in-out duration-150 cursor-pointer']) }}>
    {{ $slot }}
</button>
