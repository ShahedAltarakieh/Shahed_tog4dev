<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OdooService
{
    protected $baseUrl;
    protected $headers;

    public function __construct()
    {
        $this->baseUrl = config('services.odoo.base_url');
        $this->headers = [
            'API-KEY'       => config('services.odoo.api_key'),
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
        ];
    }

    protected function logRequest($method, $url, $payload, $response)
    {
        Log::channel('odoo')->info("ODDO API {$method} {$url}", [
            'payload'  => $payload,
            'response' => $response,
        ]);
    }

    protected function logError($method, $url, $payload, $error)
    {
        Log::channel('odoo')->error("Odoo API {$method} FAILED {$url}", [
            'payload' => $payload,
            'error'   => $error instanceof \Throwable ? $error->getMessage() : $error,
        ]);
    }

    public function post($endpoint, array $data)
    {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');

        try {
            $response = Http::withHeaders($this->headers)->post($url, $data)->throw();
            $this->logRequest('POST', $url, $data, $response->json());
            return $response->json();
        } catch (\Throwable $e) {
            $this->logError('POST', $url, $data, $e);
            throw $e;
        }
    }

    public function put($endpoint, array $data)
    {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');

        try {
            $response = Http::withHeaders($this->headers)->put($url, $data)->throw();
            $this->logRequest('PUT', $url, $data, $response->json());
            return $response->json();
        } catch (\Throwable $e) {
            $this->logError('PUT', $url, $data, $e);
            throw $e;
        }
    }

    public function delete($endpoint, array $data)
    {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');

        try {
            $response = Http::withHeaders($this->headers)->delete($url, $data)->throw();
            $this->logRequest('DELETE', $url, null, $response->json());
            return $response->json();
        } catch (\Throwable $e) {
            $this->logError('DELETE', $url, null, $e);
            throw $e;
        }
    }

    public function get($endpoint, $query = [])
    {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');

        try {
            $response = Http::withHeaders($this->headers)->get($url, $query)->throw();
            $this->logRequest('GET', $url, $query, $response->json());
            return $response->json();
        } catch (\Throwable $e) {
            $this->logError('GET', $url, $query, $e);
            throw $e;
        }
    }
}
?>