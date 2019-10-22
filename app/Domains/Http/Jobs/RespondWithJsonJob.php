<?php

namespace App\Domains\Http\Jobs;

use pierresilva\Foundation\Job;
use Illuminate\Routing\ResponseFactory;

class RespondWithJsonJob extends Job
{
    protected $status;
    protected $content;
    protected $headers;
    protected $options;
    protected $message;
    protected $meta;

    public function __construct($message, $content, $meta, $status = 200, array $headers = [], $options = 0)
    {
        $this->message = $message;
        $this->meta = $meta;
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
        $this->options = $options;
    }

    public function handle(ResponseFactory $factory)
    {
        $response = [
            'message' => $this->message,
            'data' => $this->content,
            'meta' => $this->meta,
            'status' => $this->status,
        ];

        return $factory->json($response, $this->status, $this->headers, $this->options);
    }
}
