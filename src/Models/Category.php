<?php

namespace DevDojo\Chatter\Models;

use DevDojo\Chatter\Contracts\Category as CategoryContract;
use DevDojo\Chatter\Traits\ChatterCategoryTrait;
use Illuminate\Database\Eloquent\Model;

class Category extends Model implements CategoryContract
{
	use ChatterCategoryTrait;

    protected $table = 'chatter_categories';
    public $timestamps = true;
    public $with = 'parents';

    public function discussions()
    {
        return $this->hasMany(Models::className(Discussion::class),'chatter_category_id');
    }

    public function parents()
    {
        return $this->hasMany(Models::classname(self::class), 'parent_id')->orderBy('order', 'asc');
    }
}
