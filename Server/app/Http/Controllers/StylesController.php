<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppStyle;
use Illuminate\Support\Facades\Log;
use  App\Http\Resources\AppStyleResource;
class StylesController extends Controller
{
    public function saveStyles(Request $request)
    {
        AppStyle::truncate();
        foreach ($request->styles as $style) {
            AppStyle::create([
                'key' => $style['key'],
                'value' => $style['value']
            ]);
        }
    
        return response()->json(['message' => 'Estilos guardados exitosamente']);
    }
    public function getStyles()
    {
        $styles = AppStyle::all();
        return response()->json(AppStyleResource::collection($styles));
    }
}
