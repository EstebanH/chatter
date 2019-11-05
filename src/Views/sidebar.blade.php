<ul class="nav nav-pills nav-stacked">
	@foreach ($categories as $category)
		<li>
			<a href="/{{ config('chatter.routes.home') . '/' . config('chatter.routes.category') . '/' . $category['slug'] }}">
				<div class="chatter-box" style="background-color:{{ $category['color'] }}"></div>
				{{ $category['name'] }}
			</a>

			@if (count($category['parents']))
				@include('chatter::sidebar', [ 'categories' => $category['parents'] ])
			@endif
		</li>
	@endforeach
</ul>