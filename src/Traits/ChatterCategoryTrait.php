<?php

namespace DevDojo\Chatter\Traits;

trait ChatterCategoryTrait {

	public function scopeFilterCategories($query, $slug = null) {
		return $query;
	}
}