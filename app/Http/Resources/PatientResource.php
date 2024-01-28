<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'middlename' => $this->middlename,
            'lastname' => $this->lastname,
            'birthdate' => $this->birthdate,
            'address' => $this->address,
            'gender' => $this->gender,
            'civil_status' => $this->civil_status,
            'religion' => $this->religion,
            'occupation' => $this->occupation,
            'contact_number' => $this->contact_number,
            'photo_url' =>  $this->getFirstMediaUrl()
        ];
    }
}
