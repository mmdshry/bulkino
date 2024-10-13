<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'request'    => [
                'message'  => $this->request['message'] ?? $this->request['token'] ?? null,
                'receptor' => $this->request['receptor'] ?? null,
            ],
            'response'   => [
                'return'  => [
                    'status'  => $this->response['return']['status'] ?? null,
                    'message' => $this->response['return']['message'] ?? null,
                ],
                'entries' => [
                    [
                        'messageid' => $this->response['entries'][0]['messageid'] ?? null
                    ],
                ],
            ],
            'type'       => $this->type,
            'status'     => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}