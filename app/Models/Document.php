<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'user_record_id',
        'document_name',
        'document_path',
        'file_type',
    ];

    /**
     * Get the user record for this document
     */
    public function userRecord()
    {
        return $this->belongsTo(UserRecord::class);
    }
}
