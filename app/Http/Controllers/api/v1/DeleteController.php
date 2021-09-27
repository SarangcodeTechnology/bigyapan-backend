<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function delete(Request $request){
        try {
            $model = '\\App\\Models\\'.$request->model;
            $id = $request->id;

            $model::destroy($id);
            return response()->json(['message'=>'Item Deleted Successfully.','type'=>'success'], 200);

        } catch (Exception $e) {
            return response()->json(['message'=>$e->getMessage(),'type'=>'error'], $e->getCode());
        }
    }
}
