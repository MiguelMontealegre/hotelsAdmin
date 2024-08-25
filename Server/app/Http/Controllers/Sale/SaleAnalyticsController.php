<?php
declare(strict_types=1);


namespace App\Http\Controllers\Sale;

use Carbon\Carbon;
use App\Models\User;
use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use App\Models\ApiService;
use GuzzleHttp\Psr7\Stream;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ApiServiceResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Exception\RequestException;
use App\Http\Resources\ApiService\ApiServiceResource;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class SaleAnalyticsController extends Controller
{
	public function getDatas(Request $request) 
{

    $groupBy = $request->input('groupBy');
    $startDate = null;
    $endDate = null;
    $userId = $request->input('userId');


    if ($request->has('dateRange')) { 
        $dateRange = $request->input('dateRange');
        $startDate = $dateRange[0];
        $endDate = $dateRange[1];
    }

    $evaluatedPreciResults = Payment::query();

    if ($startDate && $endDate) {
        $evaluatedPreciResults->whereBetween('createdAt', [$startDate, $endDate]);
    }

    if ($groupBy === 'day') {
        $evaluatedPreciResults->selectRaw('DATE(createdAt) as date, SUM(value) as totalValue, COUNT(*) as count');
    }
    if ($userId) {
        $evaluatedPreciResults->where('userId', $userId);
    }

    if ($groupBy === 'week') {
        $evaluatedPreciResults->selectRaw("CONCAT(YEAR(createdAt), '/', LPAD(WEEK(createdAt), 2, '0')) as date, COUNT(*) as count, SUM(value) as totalValue");
    }

    if ($groupBy === 'month') {
        $evaluatedPreciResults->selectRaw("CONCAT(YEAR(createdAt), '/', LPAD(MONTH(createdAt), 2, '0')) as date, COUNT(*) as count, SUM(value) as totalValue");
    }
    
    $evaluatedPreciResults = $evaluatedPreciResults->groupByRaw("date")->get();

    $results = [
        [
            'key' => 'Precio Ventas',
            'data' => $evaluatedPreciResults
        ],
    ];

    $total = Payment::query();

    if ($startDate && $endDate) {
        
        $total->whereBetween('createdAt', [$startDate, $endDate]);
    }
    
    $totalSales = 0;
    if ($userId) {
        $totalSales = Payment::where('userId', $userId);
        if ($startDate && $endDate) {
            $totalSales->whereBetween('createdAt', [$startDate, $endDate]);
        }
        $totalSales = $totalSales->count();
    }
   
    return response()->json([
        'data' => $results,
        'totalSales' => $totalSales,
    ])->setStatusCode(ResponseAlias::HTTP_OK);
}
	public function getPrice(Request $request)
	{
		$startDate = null;
		$endDate = null;
		if($request->input('dateRange')){
			$dateRange = $request->input('dateRange');
			$startDate = $dateRange[0];
			$endDate = $dateRange[1];
		}

	
		$price = Payment::sum('value');


        $betweenDateprice = Payment::whereBetween('createdAt', [$startDate, $endDate])->sum('value');

		
        $total = Payment::count();

		
		return response()->json(
			[
				'price' => $price,
				'betweenDateprice' => $betweenDateprice,
				'total' => $total,
			]
		)->setStatusCode(ResponseAlias::HTTP_OK);
	}

    public function getSalesByProduct(Request $request)
    {
        $startDate = null;
        $endDate = null;
        if ($request->input('dateRange')) {
            $dateRange = $request->input('dateRange');
            $startDate = $dateRange[0];
            $endDate = $dateRange[1];
        }
    
        // Obtener el número de elementos por página desde la solicitud, con un valor predeterminado de 15
        $perPage = $request->input('perPage', 15);
    
        $query = OrderProduct::query()
            ->join('orders', 'orders.id', '=', 'orderProducts.orderId')
            ->join('products', 'products.id', '=', 'orderProducts.productId')
            ->selectRaw('products.id as productId, products.title as product, SUM(orderProducts.quantity) as quantity, SUM(orderProducts.quantity * products.price) as totalValue')
            ->groupBy('orderProducts.productId', 'products.id', 'products.title');
    
        if ($startDate && $endDate) {
            $query->whereBetween('orders.createdAt', [$startDate, $endDate]);
        }
    
        // Aplicar paginación
        $salesByProduct = $query->paginate($perPage);
    
        // Mapear los resultados
        $results = $salesByProduct->items();
    
        // Transformar los resultados
        $transformedResults = collect($results)->map(function ($sale) {
            return [
                'product' => $sale->product,
                'id' => $sale->productId, // Asegurarse de incluir el ID del producto
                'quantity' => $sale->quantity,
                'totalValue' => $sale->totalValue,
            ];
        });
    
        return response()->json([
            'data' => $transformedResults,
            'pagination' => [
                'total' => $salesByProduct->total(),
                'perPage' => $salesByProduct->perPage(),
                'currentPage' => $salesByProduct->currentPage(),
                'lastPage' => $salesByProduct->lastPage(),
                'from' => $salesByProduct->firstItem(),
                'to' => $salesByProduct->lastItem(),
            ],
        ])->setStatusCode(ResponseAlias::HTTP_OK);
    }
    
    public function getProductData(Request $request) 
{
    $groupBy = $request->input('groupBy');
    $startDate = null;
    $endDate = null;
    $productId = $request->input('productId');

    if ($request->has('dateRange')) { 
        $dateRange = $request->input('dateRange');
        $startDate = $dateRange[0];
        $endDate = $dateRange[1];
    }

    $productSalesQuery = OrderProduct::query()
        ->join('orders', 'orders.id', '=', 'orderProducts.orderId')
        ->join('products', 'products.id', '=', 'orderProducts.productId');

    if ($startDate && $endDate) {
        $productSalesQuery->whereBetween('orders.createdAt', [$startDate, $endDate]);
    }

    if ($productId) {
        $productSalesQuery->where('orderProducts.productId', $productId);
    }

    if ($groupBy === 'day') {
        $productSalesQuery->selectRaw('DATE(orders.createdAt) as date, SUM(orderProducts.quantity) as totalQuantity, SUM(orderProducts.quantity * products.price) as totalValue');
    } elseif ($groupBy === 'week') {
        $productSalesQuery->selectRaw("CONCAT(YEAR(orders.createdAt), '/', LPAD(WEEK(orders.createdAt), 2, '0')) as date, SUM(orderProducts.quantity) as totalQuantity, SUM(orderProducts.quantity * products.price) as totalValue");
    } elseif ($groupBy === 'month') {
        $productSalesQuery->selectRaw("CONCAT(YEAR(orders.createdAt), '/', LPAD(MONTH(orders.createdAt), 2, '0')) as date, SUM(orderProducts.quantity) as totalQuantity, SUM(orderProducts.quantity * products.price) as totalValue");
    }

    $productSalesQuery->groupByRaw("date");

    $productSalesResults = $productSalesQuery->get();

    $results = [
        [
            'key' => 'Ventas de Productos',
            'data' => $productSalesResults
        ],
    ];

    return response()->json([
        'data' => $results,
    ])->setStatusCode(ResponseAlias::HTTP_OK);
}

}