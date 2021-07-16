<?php

namespace Bryceandy\Beem\Traits\PaymentCheckouts;

use Bryceandy\Beem\Exceptions\ConfigurationUnavailableException;
use Bryceandy\Beem\Traits\MakesHttpRequests;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;

trait HandlesPaymentCheckouts
{
    use MakesHttpRequests;

    /**
     * @param string $amount
     * @param string $transaction_id
     * @param string $reference_number
     * @param string|null $mobile
     *
     * @return Application|RedirectResponse|Redirector
     *
     * @throws ConfigurationUnavailableException
     */
    public function checkout(
        string $amount,
        string $transaction_id,
        string $reference_number,
        string $mobile = null
    )
    {
        $dataString = 'https://checkout.beem.africa/v1/checkout?';

        $data = collect(compact('amount', 'transaction_id', 'reference_number', 'mobile'))
            ->reject(fn($datum) => is_null($datum))
            ->all();

        foreach ($data as $key => $item) {
            $dataString .= "$key=$item&";
        }

        $url = (string) Str::of($dataString)->append('sendSource=true');

        $redirectUrl = $this->call($url, 'GET')->json('src');

        return redirect($redirectUrl);
    }
}