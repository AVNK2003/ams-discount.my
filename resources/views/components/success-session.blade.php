@if(session('success'))

    <div x-data="{open: true}" x-init=" () => { open = true; setTimeout(() => { open = false }, 3000);  }"
         x-show.transition.opacity.duration.1000="open"
         class="success w-44 font-medium">
            {{ session('success') }}
    </div>
@endif
