<?php

namespace DevDojo\Chatter\Contracts;

interface Discussion {
	public function category();

	public function posts();

	public function postsCount();
}