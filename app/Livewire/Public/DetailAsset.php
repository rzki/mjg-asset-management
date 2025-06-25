<?php

namespace App\Livewire\Public;

use App\Models\Asset;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class DetailAsset extends Component
{
    public $asset, $assetId;
    public function mount($assetId)
    {
        $this->asset = Asset::where('assetId', $assetId)->first();
    }
    #[Title('Detail Asset')]
    #[Layout('components.layouts.public')]
    public function render()
    {
        return view('livewire.public.detail-asset', [
            'asset' => $this->asset,
        ]);
    }
}
