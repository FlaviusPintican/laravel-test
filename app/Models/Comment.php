<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    /**
     * @var string
     */
    protected $table = 'comment';

    /**
     * @var string
     */
    protected $fillable = ['post_id', 'name', 'email', 'body'];

    /**
     * Get the post that owns the comment
     *
     * @return BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
