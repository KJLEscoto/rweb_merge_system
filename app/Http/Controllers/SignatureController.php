<?php

namespace App\Http\Controllers;

use App\Models\JobDraft;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class SignatureController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'new_signature_pad' => 'required_without:signature_admin|string',
        ]);

        $user = User::findOrFail(auth()->user()->id);
        if ($request->new_signature_pad) {
            $image = str_replace('data:image/png;base64,', '', $request->new_signature_pad);
            $imagePath = 'signatures/signature_' . time() . '.png';
            file_put_contents(public_path($imagePath), base64_decode($image));
        }


        $user->update([
            'signature' => $imagePath
        ]);
        

        return redirect()->back();
    }
}
