<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceRecordResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'employee_name' => $this->employee_name,
            'employee_role' => $this->employee_role,
            'age' => $this->age,
            'manager_name' => $this->manager_name,
            'registered_at' => $this->registered_at->format('Y-m-d H:i:s'),
        ];
    }
}
