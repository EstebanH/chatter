<?php

namespace DevDojo\Chatter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DevDojo\Chatter\Contracts\Discussion as DiscussionContract;
use Illuminate\Support\Facades\Config;

class Discussion extends Model implements DiscussionContract{

	use SoftDeletes;

	protected $table = 'chatter_discussion';
	public $timestamps = true;
	protected $fillable = ['title', 'chatter_category_id', 'user_id', 'slug', 'color'];
	protected $dates = ['deleted_at', 'last_reply_at'];

	public function user() {
		return $this->belongsTo(Config::get('chatter.user.namespace'));
	}

	public function category() {
		return $this->belongsTo(Models::className(Category::class), 'chatter_category_id');
	}

	public function posts() {
		return $this->morphMany(Models::className(Post::class), 'discussion');
	}

	public function post() {
		return $this->morphMany(Models::className(Post::class), 'discussion')->orderBy('created_at', 'ASC');
	}

	public function postsCount() {
		return $this->posts()
			->selectRaw('discussion_id, discussion_type, count(*)-1 as total')
			->groupBy(['discussion_id', 'discussion_type']);
	}

	public function users() {
		return $this->belongsToMany(Config::get('chatter.user.namespace'), 'chatter_user_discussion', 'discussion_id', 'user_id');
	}
}
