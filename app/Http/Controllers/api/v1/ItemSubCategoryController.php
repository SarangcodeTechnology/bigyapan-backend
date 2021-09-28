<?php

namespace App\Http\Controllers\api\v1;

use App\Helpers\CollectionHelper;
use App\Http\Controllers\Controller;
use App\Models\AccountType;
use App\Models\ItemSubCategory;
use Exception;
use Illuminate\Http\Request;

class ItemSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        $keyword = $request['search'];
        if ($request['search']) {
            $itemSubCategories = ItemSubCategory::all();
            $itemSubCategories = CollectionHelper::paginate($itemSubCategories->filter(function ($item) use ($keyword) {
                return false !== stripos($item, $keyword);
            })->sortBy($request['sortBy'], 0, $request['sortDesc']), $request['perPage']);
        } else {
            $itemSubCategories = ItemSubCategory::all();
            $itemSubCategories = CollectionHelper::paginate($itemSubCategories->sortBy($request['sortBy'], 0, $request['sortDesc'])->values(), $request['perPage']);
        }
        return response()->json(['type' => 'success', 'message' => 'Item categories fetched successfully.', 'errors' => null, 'data' => $itemSubCategories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => 'required|string',
            ]);

            $data = $request->all();
            $itemCategory = ItemSubCategory::create($data);
            return response()->json(['type' => 'success', 'message' => 'Item category created successfully.', 'errors' => null, 'data' => null]);
        } catch (Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage(), 'errors' => $e->getTrace(), 'data' => null], $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $itemSubCategories = ItemSubCategory::find($id);
            return response()->json(['type' => 'success', 'message' => 'Item category detail fetched successfully.', 'errors' => null, 'data' => $itemSubCategories]);
        } catch (Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage(), 'errors' => $e->getTrace(), 'data' => null], $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $itemCategory = ItemSubCategory::all()->find($id);
        $itemCategory->update($data);
        $itemCategory = ItemSubCategory::all()->find($id);
        return response()->json(['type' => 'success', 'message' => 'AccountType detail updated successfully.', 'errors' => null, 'data' => $itemCategory]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $itemCategory = ItemSubCategory::all()->find($id);
        $itemCategory->delete($itemCategory);
        return response()->json(['type' => 'success', 'message' => 'AccountType deleted successfully.', 'errors' => null, 'data' => null]);
    }
}
