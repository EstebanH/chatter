<?php

namespace DevDojo\Chatter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    
    protected $table = 'chatter_post';
    public $timestamps = true;
    protected $fillable = ['chatter_discussion_id', 'user_id', 'body', 'markdown', 'parent_id'];
    protected $dates = ['deleted_at'];

    public function discussion()
    {
        return $this->belongsTo(Models::className(Discussion::class), 'chatter_discussion_id');
    }

    public function user()
    {
        return $this->belongsTo(config('chatter.user.namespace'));
    }

	public function replies() {
		return $this->hasMany(Models::className(static::class), 'parent_id');
    }

	public function parent() {
		return $this->belongsTo(Models::className(static::class), 'parent_id');
    }
}
