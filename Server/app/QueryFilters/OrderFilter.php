<?php

namespace App\QueryFilters;

use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder;

class OrderFilter
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

		if (request()->has('filters.users')) {
			$builder->whereHas('payment', function ($query) {
				$query->where('userId', request()->input('filters.users'));
			});
		}


        $builder
			->join('payments', 'payments.id', 'orders.paymentId')
			->select(['orders.*', 'payments.value', 'payments.provider']);

        return $builder;

    }//end handle()


}//end class
