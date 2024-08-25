<?php

namespace App\QueryFilters;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Database\Eloquent\Builder;


class ParentOrderFilter
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
		if (request()->has('filters.orders')) {
			$builder->whereIn('orderId', request()->input('filters.orders'));
		}

		// Join to make searchable
		return $builder;
	} //end handle()


}//end class
