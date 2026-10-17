@if (session('status'))
    <div class="shop-container pt-6">
        <div class="rounded-md border border-success/30 bg-success/10 px-4 py-3 text-sm font-medium text-success">
            {{ session('status') }}
        </div>
    </div>
@endif
