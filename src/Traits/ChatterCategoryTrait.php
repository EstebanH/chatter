<?php

namespace DevDojo\Chatter\Traits;

trait ChatterCategoryTrait {

	public function scopeFilterCategories($query) {
		return $query;
	}
}