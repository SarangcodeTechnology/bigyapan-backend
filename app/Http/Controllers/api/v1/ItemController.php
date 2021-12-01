<?php

namespace App\Http\Controllers\api\v1;

use App\Helpers\CollectionHelper;
use App\Helpers\UploadHelper;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
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
            $items = Item::with('user', 'item_category', 'item_sub_category', 'item_images')->get();
            $items = CollectionHelper::paginate($items->filter(function ($item) use ($keyword) {
                return false !== stripos($item, $keyword);
            })->sortBy($request['sortBy'], 0, $request['sortDesc']), $request['perPage']);
        } else {
            $items = Item::with('user', 'item_category', 'item_sub_category', 'item_images')->get();
            $items = CollectionHelper::paginate($items->sortBy($request['sortBy'], 0, $request['sortDesc'])->values(), $request['perPage']);
        }
        return response()->json(['type' => 'success', 'message' => 'Items fetched successfully.', 'errors' => null, 'data' => $items]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = json_decode($request['item'], true);

        Validator::make($data, [
            'user_id' => 'required',
            'item_category_id' => 'required',
            'item_sub_category_id' => 'required',
            'item_name' => 'required|string',
            'item_price' => 'required',
        ])->validate();

        $imageData['item_image_large'] = [];

        foreach ($request->file('item_images') as $i => $item_image) {
            $user = User::all()->find($data['user_id']);
            $titleShort = strtolower(str_replace(' ', '', $user['name'] . $data['item_name'] . $i));
            array_push($imageData['item_image_large'], UploadHelper::upload('item_images', $item_image, $titleShort, 'storage/images/item-images'));
        }
        $imageData['item_image_large'] = json_encode($imageData['item_image_large']);
        $item = Item::create($data);
        $imageData['item_id'] = $item->id;
        $imageData = ItemImage::create($imageData);
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
        try {
            $items = Item::with('item_sub_category', 'item_category', 'user.user_details', 'item_images')->find($id);
            return response()->json(['type' => 'success', 'message' => 'Item fetched successfully.', 'errors' => null, 'data' => $items]);

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
        $data = json_decode($request['item'], true);
        Validator::make($data, [
            'user_id' => 'required',
            'item_category_id' => 'required',
            'item_sub_category_id' => 'required',
            'item_name' => 'required|string',
            'item_price' => 'required',
        ])->validate();

        $item = Item::all()->find($id);
        $itemImage = ItemImage::where('item_id', $id)->first();

        $imageData['item_image_large'] = [];
        foreach ($request->file('item_images') as $i => $item_image) {
            if ($request->file('item_images') != null && $request->file('item_images') != '') {
                $user = User::all()->find($data['user_id']);
                $titleShort = strtolower(str_replace(' ', '', $user['name'] . $data['item_name'] . $i));
                array_push($imageData['item_image_large'], UploadHelper::update('item_images', $item_image, $titleShort, 'storage/images/item-images', $itemImage['item_image_large']));
            } else {
                $itemImage['item_image_large'] = $imageData['item_image_large'] ?? '';
            }
        }

        $imageData['item_image_large'] = json_encode($imageData['item_image_large']);
        $item->update($data);
        $itemImage->update($imageData);
        $item = Item::all()->find($id);
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
        $item = Item::all()->find($id);
        $item->delete($item);
        return response()->json(['type' => 'success', 'message' => 'Item deleted successfully.', 'errors' => null, 'data' => null]);

    }
}
