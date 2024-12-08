<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Services\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class EmployeeController extends Controller
{
    protected EmployeeService $employeerService;

    public function __construct(EmployeeService $employeerService)
    {
        $this->employeerService = $employeerService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse | JsonResource
    {
        $employees = $this->employeerService->getAllEmployeesWithUsers();
    
        return response()->json([
            'message' => 'Retrieved successfully',
            'status' => 'success',
            'data' => EmployeeResource::collection($employees)
        ], 200, [], JSON_PRETTY_PRINT);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeRequest $request): JsonResponse | JsonResource
    {
        try
        {
            $validated = $request->validated();
            $data = $validated['data']['attributes'];
            $employeer = $this->employeerService->createWithUser($data);
            return response()->json([
                'message' => 'Employee created successfully',
                'status' => 'success',
                'data' => new EmployeeResource($employeer)
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

            $employeer = $this->employeerService->getEmployee($id);

            return response()->json([
                'message' => 'Retrieved successfully',
                'status' => 'success',
                'data' => new EmployeeResource($employeer)
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
    public function update(EmployeeRequest $request, $id): JsonResponse | JsonResource
    {
        try {
            $validated = $request->validated();
            $data = $validated['data']['attributes'];

            $employeer = $this->employeerService->updateEmployee($data, $id);

            return response()->json([
                'message' => 'Employee updated successfully',
                'status' => 'success',
                'data' => new EmployeeResource($employeer)
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
            $this->employeerService->softDeleteEmployeeAndDeactivateUser($id);
        
            return response()->json([
                'message' => 'Employee deleted successfully',
                'status' => 'success',
            ], 204);
        } 
        catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Employee not found',
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
