<?php

namespace DevDojo\Chatter\Contracts;

interface Category {
	function scopeFilterCategories($query, $slug = null);
}