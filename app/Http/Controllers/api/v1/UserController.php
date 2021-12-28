<?php

namespace App\Http\Controllers\api\v1;

use App\Helpers\CollectionHelper;
use App\Helpers\UploadHelper;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\UserDetail;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $keyword = $request['search'];
        if ($request['search']) {
            $users = User::with('user_details', 'user_details.account_type', 'user_details.address_municipality', 'user_details.address_district', 'user_details.address_province', 'user_details.address_country')->get();
            $users = CollectionHelper::paginate($users->filter(function ($item) use ($keyword) {
                return false !== stripos($item, $keyword);
            })->sortBy($request['sortBy'], 0, $request['sortDesc']), $request['perPage']);
        } else {
            $users = User::with('user_details', 'user_details.account_type', 'user_details.address_municipality', 'user_details.address_district', 'user_details.address_province', 'user_details.address_country')->get();
            $users = CollectionHelper::paginate($users->sortBy($request['sortBy'], 0, $request['sortDesc'])->values(), $request['perPage']);
        }
        return response()->json(['type' => 'success', 'message' => 'Users fetched successfully.', 'errors' => null, 'data' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        try {
//            $data = (json_decode($request->all(),true)->validate([
//                'name' => 'required|string',
//                'email' => 'email|required|string|unique:users',
//                'password' => 'required|string|confirmed|min:8',
//                'user_details.phone_number' => 'required',
//                'user_details.account_type_id' => 'required',
//            ]));
            $data = json_decode($request['user'], true);
            $data['password'] = Hash::make($data['password']);
            $titleShort = strtolower(str_replace(' ', '', $data['name']));
            $data['user_details']['user_image'] = UploadHelper::upload('user_image', $request['user_image'], $titleShort, 'storage/images/user-images');
            $user = User::create($data);
            $data['user_details']['user_id'] = $user->id;
            $user_details = UserDetail::create($data['user_details']);
            return response()->json(['type' => 'success', 'message' => 'User created successfully.', 'errors' => null, 'data' => null]);
        } catch (Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage(), 'errors' => $e->getTrace(), 'data' => null], $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            $user = User::with('user_details', 'user_details.account_type', 'user_details.address_municipality', 'user_details.address_district', 'user_details.address_province', 'user_details.address_country')->find($id);
            return response()->json(['type' => 'success', 'message' => 'User detail fetched successfully.', 'errors' => null, 'data' => $user]);
        } catch (Exception $e) {
            return response()->json(['type' => 'error', 'message' => $e->getMessage(), 'errors' => $e->getTrace(), 'data' => null], $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse|null
     */
    public function update(Request $request, $id)
    {
        $data = json_decode($request['user'], true);
        $user = User::find($id);
        $user_details = UserDetail::find($data['user_details']['id']);
        //Image Upload
        if ($request['user_image'] != null && $request['user_image'] != '') {
            $titleShort = strtolower(str_replace(' ', '', $data['name']));
            $data['user_details']['user_image'] = UploadHelper::update('user_image', $request['user_image'], $titleShort, 'storage/images/user-images', $user_details['user_image']);
        } else {
            $data['user_details']['user_image'] = $data['user_details']['user_image'] ?? '';
        }
        $user->update($data);
        $user_details->update($data['user_details']);
        $user = User::with('user_details', 'user_details.account_type', 'user_details.address_municipality', 'user_details.address_district', 'user_details.address_province', 'user_details.address_country')->find($id);
        return response()->json(['type' => 'success', 'message' => 'User detail updated successfully.', 'errors' => null, 'data' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $user = User::find($id);
        UploadHelper::deleteFile('storage/images/user-images/' . $user->image);
        $user->delete($user);
        return response()->json(['type' => 'success', 'message' => 'User deleted successfully.', 'errors' => null, 'data' => null]);

    }
}
