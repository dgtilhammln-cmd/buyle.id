<?php

namespace App\Http\Controllers;

use App\Models\CreatorBioBlock;
use App\Models\CreatorProfile;

class BioProductController extends Controller
{
    public function show(\, \)
    {
        \C:\Users\dgtil\OneDrive\Dokumen\WindowsPowerShell\Microsoft.PowerShell_profile.ps1 = CreatorProfile::where('store_slug', \)->firstOrFail();
        \   = CreatorBioBlock::where('id', \)
                    ->where('creator_id', \C:\Users\dgtil\OneDrive\Dokumen\WindowsPowerShell\Microsoft.PowerShell_profile.ps1->id)
                    ->where('type', 'custom_product')
                    ->where('is_active', true)
                    ->firstOrFail();

        \  = \C:\Users\dgtil\OneDrive\Dokumen\WindowsPowerShell\Microsoft.PowerShell_profile.ps1->bio_config ?? [];
        \   = \C:\Users\dgtil\OneDrive\Dokumen\WindowsPowerShell\Microsoft.PowerShell_profile.ps1->bio_theme ?? 'theme1';

        return view('bio.product_show', compact('profile', 'block', 'config', 'theme', 'username'));
    }
}
