<div>
    <div class="row align-items-center">
        <div class="col-lg-4 text-center">
            {{-- Replace with your barcode image generation --}}
            <img src="{{ asset('storage/' . $asset->barcode) }}" alt="Barcode" class="img-fluid" />
        </div>
        <div class="col-lg-8 text-center">
            <h5>{{ $asset->asset_code }}</h5>
        </div>
    </div>
</div>