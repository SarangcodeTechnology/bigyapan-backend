<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\AccountType;
use App\Models\Country;
use Exception;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function syncResources(Request $request)
    {
        try {
            $accountTypes = AccountType::all();
            $addresses = Country::with('provinces.districts.municipals')->get();
            return response()->json([
                'type' => 'success',
                'message' => 'Resources synced successfully',
                'data' => compact('accountTypes','addresses')
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'type' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }
}
