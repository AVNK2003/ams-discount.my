@if(session('success'))
<div x-data="{open: true}" x-show.transition.in.duration.500ms.out.duration.300ms="open"  class="fixed top-0 left-0 h-screen w-screen flex items-center justify-center bg-black bg-opacity-75 z-50">
    <div class="rounded-md bg-gradient-to-b from-[#222222] via-black to-[#222222] py-2 px-4 max-w-[90%] border shadow-lg">
        <div class="p-8 text-center mx-auto">
            {{session('success')}}
        </div>
        <button wire:click="done()" @click="open = false" class="flex px-6 py-2 mx-auto mb-4 border rounded focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Ок
        </button>
    </div>
</div>
@endif
