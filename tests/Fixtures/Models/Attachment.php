<?php

namespace Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'extension',
        'mime_type',
        'md5',
        'type',
        'size',
    ];
}
