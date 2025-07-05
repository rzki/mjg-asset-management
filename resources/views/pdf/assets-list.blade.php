<!DOCTYPE html>
<html>
<head>
    <title>Assets List</title>
    <style>
        body { font-family: sans-serif; }
        .row { display: flex; }
        .cell { width: 40%; padding: 5px 5px; box-sizing: border-box; text-align: left; border: solid black 1px; margin: 5px }
        .barcode-asset {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .barcode-asset img {
            width: 60px;
            height: auto;
            display: block;
            flex-shrink: 0;
        }
        .barcode-asset span {
            font-size: 15px;
            font-weight: bold;
            white-space: nowrap;
            letter-spacing: 1px;
            max-width: 120px;
            /* overflow: hidden;
            text-overflow: ellipsis; */
            display: inline-block;
        }
    </style>
</head>
<body>
    <h2>Assets List</h2>
    @php
$chunks = $assets->chunk(2);
    @endphp
    @foreach($chunks as $chunk)
        <div class="row">
            @foreach($chunk as $asset)
                <div class="cell">
                    <div class="barcode-asset">
                        {{-- <img src="{{ storage_path('app/public/'.$asset->barcode) }}" alt="barcode"> --}}
                        <img src="{{ asset('storage/' . $asset->barcode) }}" alt="barcode">
                        <span>{{ $asset->asset_code }}</span>
                    </div>
                </div>
            @endforeach
            @for($i = $chunk->count(); $i < 2; $i++)
                <div class="cell"></div>
            @endfor
        </div>
    @endforeach
</body>
</html>