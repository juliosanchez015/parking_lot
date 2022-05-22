<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use function __;
use function response;
use function with;

class BaseController extends Controller
{
    protected $model = '';
    protected $resource = '';
    protected $collectionResource = '';


    public function __construct() {
    }

    public function index(Request $request)
    {
        $class = $this->model;
        $table_name = with(new $class)->getTable();
        $search = $request->get('search', "");
        $orderBy = $request->get('orderBy', "");
        $orderDir = $request->get('orderDir', "");
        $perPage = $request->get('per_page', null);
        $populate = filter_var($request->get('populate', false),FILTER_VALIDATE_BOOLEAN);

        $columns = DB::getSchemaBuilder()->getColumnListing($table_name);
        unset($columns[0]);

        $query = $class::select();
        $populate ? $query->with($class::relationship()) : null;

        $searchClauses = [];

        if(!empty($search)) {
            foreach ($columns as $column) {
                array_push($searchClauses, [$column, 'LIKE', '%' . $search . '%']);
            }
        }

        if (isset($request->trash) && $request->trash == 1) {
            $query->onlyTrashed()->where(function ($q) use ($searchClauses) {
                foreach ($searchClauses as $clause) {
                    $q->orWhere($clause[0], $clause[1], $clause[2]);
                }
            });
        } else {
            $query->where(function ($q) use ($searchClauses) {
                foreach ($searchClauses as $clause) {
                    $q->orWhere($clause[0], $clause[1], $clause[2]);
                }
            });
        }

        if ($orderBy != "") {
            if (in_array(explode('.', $orderBy)[0], $class::relationship())) {
                if ($orderDir == 'desc') {
                    $elements = $query->paginate($perPage)->sortByDesc($orderBy)->values();
                } else {
                    $elements = $query->paginate($perPage)->sortBy($orderBy)->values();
                }
            } else {
                $elements = $query->orderBy($orderBy, $orderDir)->paginate($perPage);
            }
        } else {
            $elements = $query->paginate($perPage);
        }

        return new $this->collectionResource($elements, $populate);
    }

    public function show(Request $request, $id)
    {
        $class = $this->model;

        $this->resource::withoutWrapping();
        $item = $this->model::find($id);

        $populate = $request->get('populate', false);

        if (!$item) {
            return response()->json([
                'message' => __('entities.IDNotFound'),
                'error'   => __('entities.IDNotFound'),
            ], 404);
        }

        if ($populate) {
            $class::relationship() ? $item->with($class::relationship()) : null;
        }

        return new $this->resource($item, $populate);
    }

    public function store(Request $request)
    {
        $class = $this->model;

        $validator = Validator::make($request->all(), $class::createRules());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $object = new $class($request->all());
        $object->save();
        return new $this->resource($object);
    }

    public function update(Request $request, $id)
    {
        $class = $this->model;

        $rules = $class::updateRules();
        foreach ($rules as $key => $value) {
            if(is_string($value)){
                if (strpos($value, 'unique') !== false) {
                    $rules[ $key ] = $value . ',' . $key . ',' . $id;
                }
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $item = $class::find($id);

        if (!$item) {
            return response()->json([
                'message' => __('entities.IDNotFound'),
                'error'   => __('entities.IDNotFound'),
            ], 404);
        }

        $item->update($request->all());

        return new $this->resource($item);
    }

    public function destroy($id)
    {
        $item = $this->model::find($id);
        if (!$item) {
            return response()->json([
                'message' => __('entities.IDNotFound'),
                'error'   => __('entities.IDNotFound'),
            ], 404);
        }

        $this->destroySingleItem($item);

        return response()->json([
            'message' => __('entities.SuccessfullyDeleted'),
        ], 200);

    }

    public function destroyBatch(Request $request)
    {
        $returnMessage = "";

        if ($request->items) {
            foreach ($request->items as $item_id) {
                $item = $this->model::find($item_id);
                if (!$item) {
                    $returnMessage .= $item_id . ": " . __('entities.IDNotFound') . "\n";
                } else {
                    $this->destroySingleItem($item);
                    $returnMessage .= $item_id . ": " . __('entities.SuccessfullyDeleted') . "\n";
                }
            }
        }

        return response()->json([
            'message' => $returnMessage,
        ], 200);
    }

    public function restore($id)
    {
        $item = $this->model::withTrashed()->find($id);
        if (!$item) {
            return response()->json([
                'message' => __('entities.IDNotFound'),
                'error'   => __('entities.IDNotFound'),
            ], 404);
        }

        $item->restore();
        return new $this->resource($item);
    }

    public function restoreBatch(Request $request)
    {

        $returnMessage = "";

        if ($request->items) {
            foreach ($request->items as $item_id) {
                $item = $this->model::onlyTrashed()->find($item_id);
                if (!$item) {
                    $returnMessage .= $item_id . ": " . __('entities.DeletedIDNotFound') . "\n";
                } else {
                    $item->restore();
                    $returnMessage .= $item_id . ": " . __('entities.SuccessfullyRestored') . "\n";
                }
            }
        }

        return response()->json([
            'message' => $returnMessage,
        ], 200);
    }

    /**
     * @param $item
     */
    public function destroySingleItem($item): void
    {
        $item->update(['deleted_by' => Auth::user()->id]);
        $item->delete();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function notAllowedResponse()
    {
        return response()->json([
            'message' => __('entities.NotAllowed'),
            'error'   => __('entities.NotAllowed'),
        ], 403);
    }
}
