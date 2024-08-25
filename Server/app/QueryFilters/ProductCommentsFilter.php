<?php

namespace App\QueryFilters;


use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Database\Eloquent\Builder;


class ProductCommentsFilter
{


    /**
     * Handle Pagination Pipeline
     *
     * @param  $request
     * @param  Closure $next
     * @return Builder
     */
    public function handle($request, Closure $next): Builder
    {
        $builder = $next($request);
  
		$builder->whereNull('commentId')
			->whereNull('replyId');

		if (request()->has('filters.products')) {
            $builder->whereIn('productId', request()->input('filters.products'));
        }

		$orderDirection = request()->input('direction', 'ASC');
		$orderBy = request()->input('orderBy', 'createdAt');
		$builder->orderBy($orderBy, $orderDirection);


        return $builder;

    }//end handle()


}//end class
