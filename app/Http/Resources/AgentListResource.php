<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgentListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'agent_id' => $this->agent_id,
            'agent_name' => $this->agent_name,
            'agent_email' => $this->agent_email,
            'lob' => $this->hierarchy->c1,
            'campaign' => $this->hierarchy->c3,
            'vendor' => $this->hierarchy->c2,
            'location' => $this->hierarchy->c4,
            'doj' => $this->doj,
            'effective_date' => $this->EffectiveDate,
            'status' => $this->Status,
            'supervisor_id' => $this->Level_1['userId'],
            'supervisor_name' => $this->Level_1['name'],
            'manager1_id' => $this->Level_2['userId'],
            'manager1_name' => $this->Level_2['name'],
            'manager2_id' => $this->Level_3['userId'],
            'manager2_name' => $this->Level_3['name'],
            'added_on' => $this->AddedOn,
            'updated_at' => $this->updated_at
        ];

        return $data;
    }
}
