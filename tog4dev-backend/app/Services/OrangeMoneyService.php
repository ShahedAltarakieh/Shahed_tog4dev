<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class OrangeMoneyService
{
    private string $endpointPath = 'CliQTransaction/ProcessTransactionBusinessWallet';

    private function encrypt(string $data): string
    {
        return openssl_encrypt(
            $data,
            'AES-256-CBC',
            config('orange_money.secret'),
            0,
            config('orange_money.iv')
        );
    }

    private function generateSignature(array $params): string
    {
        $apiKey = config('orange_money.api_key');

        $rawString = $apiKey .
            $params['Amount'] .
            ($params['isConfirmed'] ? 'True' : 'False') .
            $params['SenderWallet'] .
            $params['OTP'] .
            $params['AgentUserName'] .
            $params['AgentUserPassword'] .
            $apiKey;

        return hash('sha256', $rawString);
    }

    public function prepareTransaction(array $params): array
    {
        $params['AgentUserName']     = config('orange_money.agent_username');
        $params['AgentUserPassword'] = config('orange_money.agent_password');

        $signature = $this->generateSignature($params);

        return [
            'AgentUserName'     => $this->encrypt($params['AgentUserName']),
            'AgentUserPassword' => $this->encrypt($params['AgentUserPassword']),
            'SenderWallet'      => $this->encrypt($params['SenderWallet']),
            'Amount'            => $this->encrypt($params['Amount']),
            'isConfirmed'       => $params['isConfirmed'],
            'Signature'         => $signature,
            'OTP'               => $this->encrypt($params['OTP']),
        ];
    }

    public function sendTransaction(array $payload): array
    {
        $url = rtrim(config('orange_money.api_base_url'), '/') . '/' . $this->endpointPath;

        $response = Http::withoutVerifying()
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ])
            ->post($url, $payload);

        return [
            'status'   => $response->status(),
            'response' => $response->json(),
        ];
    }
}
?>