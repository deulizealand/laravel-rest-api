<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FoodsResource extends JsonResource
{
    public $status;
    public $message;
    public $action;

    public function __construct($status, $message,  $resource = null, $action = null)
    {
        $this->status  = $status;
        $this->message = $message;
        parent::__construct($resource);
        $this->action = $action;
    }

    public function toArray($request)
    {
        return [
            'success' => $this->status,
            'message' => $this->message,
            'data' => $this->resource,
            'action' => $this->action,
            'time' => date('Y-m-d H:i:s'),
        ];
    }
}
