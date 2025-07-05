<!DOCTYPE html>
<html>
<head>
    <title>Assets List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .barcode-asset {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .barcode-asset img {
            width: 45px;
            height: auto;
            display: block;
            flex-shrink: 0;
        }
        h6 {
            margin: 0;
            font-size: 12px;
            text-align: center;
        }
    </style>
</head>
<body class="bg-white">
    @php
        $chunks = $assets->chunk(3);
    @endphp
    <div class="container-fluid my-4">
        @foreach($chunks as $chunk)
            <div class="row mb-3">
                @foreach($chunk as $asset)
                    <div class="col-4">
                        <div class="card p-2 border border-dark rounded-0">
                            <div class="barcode-asset">
                                <img src="{{ storage_path('app/public/'.$asset->barcode) }}" alt="barcode">
                                <h6 class="mx-auto">{{ $asset->asset_code }}</h6>
                            </div>
                        </div>
                    </div>
                @endforeach
                @for($i = $chunk->count(); $i < 3; $i++)
                    <div class="col-6"></div>
                @endfor
            </div>
        @endforeach
    </div>
</body>
</html>
