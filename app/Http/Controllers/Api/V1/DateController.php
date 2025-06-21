<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\DateService;
use App\Http\Controllers\Controller;
use App\Http\Resources\DateResource;
use App\Http\Requests\Requests\Api\V1\StoreDateRequest;

class DateController extends Controller
{
    protected $service;

    public function __construct(DateService $service)
    {
        $this->service = $service;
        $this->middleware(['check.id.token.name']);
    }

    public function index()
    {
        return DateResource::collection($this->service->all());
    }

    public function store(StoreDateRequest $request)
    {
        $Date = $this->service->create($request->validated());
        return new DateResource($Date);
    }

    public function show($id)
    {
        try {
            $date = $this->service->find($id); // should throw ModelNotFoundException if not found
            return response()->json([
                'code' => 200,
                'message' => 'data retrieved successfully',
                'data' => new DateResource($date)
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'code' => 404,
                'message' => 'data not found',
                'data' => null
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Something went wrong',
                'data' => null,
                'error' => $e->getMessage() // Optional: remove in production
            ], 500);
        }
    }

    public function update(StoreDateRequest $request, $id)
    {
        $Date = $this->service->update($id, $request->validated());
        return new DateResource($Date);
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
