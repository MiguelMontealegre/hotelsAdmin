<?php

namespace App\QueryFilters;

use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder;

class OrderProductFilter
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

        $builder
			->join('products', 'products.id', 'orderProducts.productId')
			->select(['orderProducts.*', 'products.title', 'products.description', 'products.price']);

        return $builder;

    }//end handle()


}//end class
