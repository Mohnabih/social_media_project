<?php

namespace App\Models\Post;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'model',
        'media',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $fillable = [
        'user_id',
        'parent_id',
        'index',
        'content',
        'status',
        'type',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'sharing_post',
        'has_media',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'user_id' => 'integer',
        'parent_id' => 'integer',
        'index' => 'integer',
        'content' => 'string',
        'status' => 'integer',
        'type' => 'integer',
    ];

    /**
     * Add SharedPost to api results
     * @return bool
     */
    public function getSharingPostAttribute()
    {
        return $this->parent ? true : false;
    }

    /**
     * Add has_media to api results
     * @return bool
     */
    public function getHasMediaAttribute()
    {
        return $this->media->count() > 0 ? true : false;
    }

    /**
     * Get the parent model (profile or page).
     *  @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * Get the user that owns the post.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the post that owns the post.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function parent()
    {
        return $this->belongsTo(Post::class, 'parent_id');
    }

    /**
     * Get the shares for the blog post.
     */
    public function shares()
    {
        return $this->hasMany(Post::class, 'parent_id');
    }

    /**
     * Get all of the post's media.
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }
}
