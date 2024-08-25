<?php

namespace App\QueryFilters;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Database\Eloquent\Builder;


class CategoriesFilter
{


	/**
	 * Handle Filter
	 *
	 * @param  $request
	 * @param  Closure $next
	 * @return Builder
	 */
	public function handle($request, Closure $next): Builder
	{
		$builder = $next($request);
		if (request()->has('filters.categories')) {
			$builder->whereHas('categories', function ($query) {
				$query->where('categoryId', request()->input('filters.categories'));
			});
		}

		// Join to make searchable
		return $builder;
	} //end handle()


}//end class
