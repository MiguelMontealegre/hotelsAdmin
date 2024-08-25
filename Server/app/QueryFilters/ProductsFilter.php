<?php

namespace App\QueryFilters;


use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Database\Eloquent\Builder;


class ProductsFilter
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
        // Join to make searchable
        $builder
            ->select(
                [
                    'products.*'
                ]
            );

        if (request()->has('q')) {
            $q = request()->get('q');
            $builder->where(
                function ($query) use ($q) {
                    $query->whereRaw('products.title LIKE ?', "%{$q}%")
                        ->orWhereRaw('products.description LIKE ?', "%{$q}%")
                        ->orWhereRaw('products.price LIKE ?', "%{$q}%")
                        ->orWhereRaw('products.discount LIKE ?', "%{$q}%");
                }
            );
        }

		if (request()->has('filters.pin')) {
			if(request()->input('filters.pin')[0] == true){
				$builder->where('pin', true);
			}
		}


		if (request()->has('filters.categories')) {
			$builder->whereHas('categories', function ($query) {
				$query->where('categoryId', request()->input('filters.categories'));
			});
		}


		if (request()->has('filters.tags')) {
			$builder->whereHas('tags', function ($query) {
				$query->where('tagId', request()->input('filters.tags'));
			});
		}


		if ( request()->has('filters.lowValue') && request()->has('filters.highValue')) {
            $low = intval(request()->input('filters.lowValue')[0]);
			$high = intval(request()->input('filters.highValue')[0]);
            $builder->where('price', '>=', $low)
            	->where('price', '<=', $high);
        }


		if ( request()->has('filters.discount')) {
			$discount = intval(request()->input('filters.discount')[0]);
			if($discount < 0){
				$builder->where('discount', '<=', abs($discount));
			} else {
				$builder->where('discount', '>=', $discount);
			}
        }


		if(request()->has('filters.userLikes')){
			$builder->whereHas('likes', function ($query) {
				$query->where('userId', request()->input('filters.userLikes'));
			});
		}


        if (request()->boolean('filters.archivedInd.0')) {
            $builder->where(
                function ($query) {
                    $query->whereNotNull('archivedAt')
                        ->orWhereNotNull('deletedAt');
                }
            );
        } else {
            $builder->whereNull('deletedAt')
                ->whereNull('archivedAt');
        }

        return $builder;

    }//end handle()


}//end class
