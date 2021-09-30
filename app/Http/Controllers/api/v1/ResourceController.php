<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\AccountType;
use App\Models\Country;
use App\Models\ItemCategory;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function syncResources(Request $request)
    {
        try {
            $accountTypes = AccountType::all();
            $addresses = Country::with('provinces.districts.municipals')->get();
            $itemCategories = ItemCategory::with('item_sub_categories')->get();
            $users = User::all();
            return response()->json([
                'type' => 'success',
                'message' => 'Resources synced successfully',
                'data' => compact('accountTypes','addresses','itemCategories','users')
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'type' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }
}
