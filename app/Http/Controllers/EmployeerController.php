<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeerRequest;
use App\Http\Resources\EmployeerResource;
use App\Models\Employeer;
use App\Services\EmployeerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class EmployeerController extends Controller
{
    protected EmployeerService $employeerService;

    public function __construct(EmployeerService $employeerService)
    {
        $this->employeerService = $employeerService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse | JsonResource
    {
        $employeers = $this->employeerService->getAllEmployeersWithUsers();
    
        return response()->json([
            'message' => 'Retrieved successfully',
            'status' => 'success',
            'data' => EmployeerResource::collection($employeers)
        ], 200, [], JSON_PRETTY_PRINT);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeerRequest $request): JsonResponse | JsonResource
    {
        $employeer = $this->employeerService->create($request->validated());
        return new EmployeerResource($employeer);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse | JsonResource
    {
        try {

            $employeer = $this->employeerService->getEmployeer($id);

            return response()->json([
                'message' => 'Retrieved successfully',
                'status' => 'success',
                'data' => new EmployeerResource($employeer)
            ], 200, [], JSON_PRETTY_PRINT);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'error',
            ], 403);
        }
    }   

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeerRequest $request, $id): JsonResponse | JsonResource
    {
        $employeer = Employeer::findOrFail($id);
        $employeer->update($request->validated());

        return new EmployeerResource($employeer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $employeer = Employeer::findOrFail($id);
        $this->employeerService->delete($employeer->id);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
