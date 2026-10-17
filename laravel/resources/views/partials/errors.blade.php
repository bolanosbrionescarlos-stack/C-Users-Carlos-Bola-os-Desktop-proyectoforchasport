@if ($errors->any())
    <div class="rounded-md border border-danger/30 bg-danger/10 px-4 py-3 text-sm text-danger">
        <ul class="list-inside list-disc space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
