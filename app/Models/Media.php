<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'src_url',
        'src_icon',
        'src_thum',
        'collection_name',
        'media_type',
        'mime_type',
        'size'
    ];

     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'src_url'=>'string',
        'src_icon'=>'string',
        'src_thum'=>'string',
        'collection_name'=>'string',
        'media_type'=>'string',
        'mime_type'=>'string',
        'size'=>'integer'
    ];

    /**
     * Get the parent model (photo or video).
     */
    public function model()
    {
        return $this->morphTo();
    }
}
