<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\URL;

class RequestMedia extends Model
{
    protected $table = 'request_media';

    protected $fillable = [
        'request_id',
        'path',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }

    /** Temporary signed URL so apps can load private files without auth headers. */
    public function temporaryUrl(?\DateTimeInterface $expiresAt = null): string
    {
        return URL::temporarySignedRoute(
            'request-media.show',
            $expiresAt ?? now()->addHour(),
            ['media' => $this->id],
        );
    }
}
