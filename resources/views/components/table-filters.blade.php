<form method="GET" class="mb-3">
    <div class="row align-items-end">

        {{-- Search --}}
        <div class="col-md-4">
            <label class="small text-muted">Search</label>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="form-control"
                placeholder="Search..."
            >
        </div>

        {{-- Filters slot --}}
        {{ $slot }}

        {{-- Actions --}}
        <div class="col-md-2">
            <button class="btn btn-primary w-100">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>

    </div>
</form>
