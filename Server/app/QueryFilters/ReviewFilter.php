<?php

namespace App\QueryFilters;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ReviewFilter
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
		if (request()->has('filters.pin')) {
			if(request()->input('filters.pin')[0] == true){
				$builder->where('pin', true);
			}
		}

        return $builder;

    }//end handle()


}//end class
