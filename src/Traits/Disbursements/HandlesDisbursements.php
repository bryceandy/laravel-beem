<?php

namespace Bryceandy\Beem\Traits\Disbursements;

use Bryceandy\Beem\Exceptions\ConfigurationUnavailableException;
use Bryceandy\Beem\Traits\MakesHttpRequests;
use Illuminate\Http\Client\Response;

trait HandlesDisbursements
{
    use MakesHttpRequests;

    /**
     * @param string $amount
     * @param string $clientReferenceId
     * @param string $accountNumber
     * @param string $walletNumber
     * @param string $walletCode
     * @param string $currency
     *
     * @return Response
     *
     * @throws ConfigurationUnavailableException
     */
    public function disburse(
        string $amount,
        string $clientReferenceId,
        string $accountNumber,
        string $walletNumber,
        string $walletCode,
        string $currency = 'TZS'
    ): Response
    {
        $data = [
            'amount' => $amount,
            'client_reference_id' => $clientReferenceId,
            'source' => [
                'account_no' => $accountNumber,
                'currency' => $currency,
            ],
            'destination' => [
                'wallet_number' => $walletNumber,
                'wallet_code' => $walletCode,
                'currency' => $currency,
            ],
        ];

        return $this->call(
            'https://apipay.beem.africa/webservices/disbursement/transfer',
            'POST',
            $data
        );
    }
}