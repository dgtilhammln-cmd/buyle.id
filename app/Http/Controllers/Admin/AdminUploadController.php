<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminUploadController extends Controller
{
    use HandlesImageUpload;

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                $path = $this->storeWebP($file, 'uploads/editor', 1200, 800, 85);
                return response()->json([
                    'url' => asset('storage/' . $path)
                ]);
            }
        }
        return response()->json(['error' => 'No image found'], 400);
    }
}
