<?php

namespace App\Livewire\Public;

use App\Models\ITAsset;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class AssetQrCodeTemplate extends Component
{
    public $asset, $assetId;

    public function mount($assetId)
    {
        $this->asset = ITAsset::where('assetId', $assetId)->first();
    }
    #[Title('Asset QR Code')]
    #[Layout('components.layouts.public')]
    public function render()
    {
        return view('livewire.public.asset-qr-code-template', [
            'asset' => $this->asset
        ]);
    }
}
