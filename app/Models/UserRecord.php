<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRecord extends Model
{
    protected $table = 'user_records';

    protected $fillable = [
        'name',
        'passport',
        'ircc',
        'email',
        'nid_number',
        'father_name',
        'mother_name',
        'user_image',
    ];

    /**
     * Get the documents for the user record
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
