<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VerificationFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VerificationFileController extends Controller
{
    public function show(Request $request, VerificationFile $verificationFile): StreamedResponse
    {
        $this->authorize('view', $verificationFile);

        $disk = Storage::disk($verificationFile->disk);

        abort_unless($disk->exists($verificationFile->path), 404);

        return response()->stream(function () use ($disk, $verificationFile): void {
            $stream = $disk->readStream($verificationFile->path);

            if (! is_resource($stream)) {
                return;
            }

            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => $verificationFile->mime_type ?: 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="'.$verificationFile->file_type.'"',
            'Cache-Control' => 'private, max-age=300',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
