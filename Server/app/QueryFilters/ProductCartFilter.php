<?php

namespace App\QueryFilters;

use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ProductCartFilter
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
			->join('products', 'products.id', 'cartProducts.productId')
			->select(['cartProducts.*', 'products.title', 'products.description', 'products.price']);

        return $builder;

    }//end handle()


}//end class
