<?php

namespace App\Http\Controllers\api\v1;

use App\Helpers\CollectionHelper;
use App\Http\Controllers\Controller;
use App\Models\AccountType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $keyword = $request['search'];
        if ($request['search']) {
            $accountTypes = AccountType::all();
            $accountTypes = CollectionHelper::paginate($accountTypes->filter(function ($item) use ($keyword) {
                return false !== stripos($item, $keyword);
            })->sortBy($request['sortBy'], 0, $request['sortDesc']), $request['perPage']);
        } else {
            $accountTypes = AccountType::all();
            $accountTypes = CollectionHelper::paginate($accountTypes->sortBy($request['sortBy'], 0, $request['sortDesc'])->values(), $request['perPage']);
        }
        return response()->json(['type' => 'success', 'message' => 'AccountTypes fetched successfully.', 'errors' => null, 'data' => $accountTypes]);
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
            $data = $request->validate([
                'title' => 'required|string',
            ]);

            $data = $request->all();
            $accountType = AccountType::create($data);
            return response()->json(['type' => 'success', 'message' => 'AccountType created successfully.', 'errors' => null, 'data' => null]);
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
            $accountType = AccountType::all()->find($id);
            return response()->json(['type' => 'success', 'message' => 'AccountType detail fetched successfully.', 'errors' => null, 'data' => $accountType]);
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
        $data = $request->all();
        $accountType = AccountType::all()->find($id);

        $accountType->update($data);

        $accountType = AccountType::all()->find($id);
        return response()->json(['type' => 'success', 'message' => 'AccountType detail updated successfully.', 'errors' => null, 'data' => $accountType]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $accountType = AccountType::all()->find($id);
        $accountType->delete($accountType);
        return response()->json(['type' => 'success', 'message' => 'AccountType deleted successfully.', 'errors' => null, 'data' => null]);
    }
}
