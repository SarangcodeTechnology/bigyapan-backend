<?php

namespace App\Http\Controllers\api\v1;

use App\Helpers\CollectionHelper;
use App\Http\Controllers\Controller;
use App\Models\ItemSubCategory;
use Exception;
use Illuminate\Http\Request;

class ItemSubCategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        $keyword = $request['search'];
        if ($request['search']) {
            $itemSubCategories = ItemSubCategory::with('item_category')->get();
            $itemSubCategories = CollectionHelper::paginate($itemSubCategories->filter(function ($item) use ($keyword) {
                return false !== stripos($item, $keyword);
            })->sortBy($request['sortBy'], 0, $request['sortDesc']), $request['perPage']);
        } else {
            $itemSubCategories = ItemSubCategory::with('item_category')->get();
            $itemSubCategories = CollectionHelper::paginate($itemSubCategories->sortBy($request['sortBy'], 0, $request['sortDesc'])->values(), $request['perPage']);
        }
        return response()->json(['type' => 'success', 'message' => 'Item sub categories fetched successfully.', 'errors' => null, 'data' => $itemSubCategories]);
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
                'item_category_id' => 'required',
                'title' => 'required|string',
            ]);

            $data = $request->all();
            $itemSubCategory = ItemSubCategory::create($data);
            return response()->json(['type' => 'success', 'message' => 'Item sub category created successfully.', 'errors' => null, 'data' => null]);
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
            $itemSubCategories = ItemSubCategory::with('item_category')->find($id);
            return response()->json(['type' => 'success', 'message' => 'Item sub category detail fetched successfully.', 'errors' => null, 'data' => $itemSubCategories]);
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
        $itemSubCategory = ItemSubCategory::all()->find($id);
        $itemSubCategory->update($data);
        $itemSubCategory = ItemSubCategory::all()->find($id);
        return response()->json(['type' => 'success', 'message' => 'Item sub category detail updated successfully.', 'errors' => null, 'data' => $itemSubCategory]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $itemSubCategory = ItemSubCategory::all()->find($id);
        $itemSubCategory->delete($itemSubCategory);
        return response()->json(['type' => 'success', 'message' => 'Item sub category deleted successfully.', 'errors' => null, 'data' => null]);
    }
}
