@if ($errors->any())
    <div class="errors">
        <div class="font-medium">
            При заполнении возникли ошибки:
        </div>

        <ul class="mt-3 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
