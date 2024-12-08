<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeerRequest;
use App\Http\Resources\EmployeerResource;
use App\Services\EmployeerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        try
        {
            $validated = $request->validated();
            $data = $validated['data']['attributes'];
            $employeer = $this->employeerService->createWithUser($data);
            return response()->json([
                'message' => 'Employeer created successfully',
                'status' => 'success',
                'data' => new EmployeerResource($employeer)
            ], 201, [], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'error',
            ], 403);
        }
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
        try {
            $validated = $request->validated();
            $data = $validated['data']['attributes'];

            $employeer = $this->employeerService->updateEmployeer($data, $id);

            return response()->json([
                'message' => 'Employeer updated successfully',
                'status' => 'success',
                'data' => new EmployeerResource($employeer)
            ], 200, [], JSON_PRETTY_PRINT);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'error',
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->employeerService->softDeleteEmployeerAndDeactivateUser($id);
        
            return response()->json([
                'message' => 'Employeer deleted successfully',
                'status' => 'success',
            ], 204);
        } 
        catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Employeer not found',
                'status' => 'error',
            ], 404);
        } 
        catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'error',
            ], 403);
        }
    }
        
}
