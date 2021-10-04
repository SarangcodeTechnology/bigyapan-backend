<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
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

        } else {
            $items = Item::with('user','item_category','item_sub_category')->paginate();
            return response()->json(['type' => 'success', 'message' => 'Items fetched successfully.', 'errors' => null, 'data' => $items]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required',
            'item_category_id' => 'required',
            'item_sub_category_id' => 'required',
            'item_name' => 'required|string',
            'item_price' => 'required',
        ]);

        $data = $request->all();
        $item = Item::create($data);
        return response()->json(['type' => 'success', 'message' => 'Item created successfully', 'errors' => null, 'data' => $item]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try{
            $items= Item::with('item_sub_category', 'item_category')->find($id);
            return response()->json(['type' => 'success', 'message' => 'Item fetched successfully.', 'errors' => null, 'data' => $items]);

        }
        catch(Exception $e)
        {
            return response()->json(['type'=>'error','message'=>$e->getMessage(),'errors'=> $e->getTrace(),'data'=>null],$e->getCode());
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
      $data= $request->all();
      $item= Item::all()->find($id);
      $item->update($data);
      $item= Item::all()->find($id);
      return response()->json(['type' => 'success', 'message' => 'Item updated successfully.', 'errors' => null, 'data' => $item]);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
      $item=Item::all()->find($id);
      $item->delete($item);
      return response()->json(['type' => 'success', 'message' => 'Item deleted successfully.', 'errors' => null, 'data' => null]);

    }
}
