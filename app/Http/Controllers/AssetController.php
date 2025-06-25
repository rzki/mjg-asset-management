<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function show(Asset $asset)
    {
        // Logic to show the asset details
        return view('assets.show', compact('asset'));
    }
}
