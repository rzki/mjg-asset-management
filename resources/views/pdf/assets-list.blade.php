<!DOCTYPE html>
<html>
<head>
    <title>Assets List</title>
    <style>
        body { font-family: sans-serif; }
        .row { display: flex; }
        .cell { width: 15%; padding: 10px; box-sizing: border-box; text-align: center; }
        .barcode-asset {
            display: flex;
            align-items: center;
            /* justify-content: center; */
            gap: 15px;
            margin-bottom: 10px;
        }
        .barcode-asset img {
            width: 70px;
            height: auto;
            display: block;
        }
        .barcode-asset span {
            font-size: 15px;
            font-weight: bold;
            white-space: nowrap;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <h2>Assets List</h2>
    @php
        $chunks = $assets->chunk(5);
    @endphp
    @foreach($chunks as $chunk)
        <div class="row">
            @foreach($chunk as $asset)
                <div class="cell">
                    <div class="barcode-asset">
                        {{-- <img src="{{ storage_path('app/public/'.$asset->barcode) }}" alt="barcode"> --}}
                        <img src="{{ asset('storage/'.$asset->barcode) }}" alt="barcode">
                        <span>{{ $asset->asset_code }}</span>
                    </div>
                </div>
            @endforeach
            @for($i = $chunk->count(); $i < 5; $i++)
                <div class="cell"></div>
            @endfor
        </div>
    @endforeach
</body>
</html>