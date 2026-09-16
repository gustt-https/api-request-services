<?php

namespace App\Http\Controllers\Api\V1\Requests;

use App\Http\Controllers\Controller;
use App\Models\RequestMedia;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RequestMediaController extends Controller
{
    public function show(RequestMedia $media): StreamedResponse
    {
        $disk = Storage::disk('private');

        abort_unless($disk->exists($media->path), 404);

        return $disk->response($media->path);
    }
}
