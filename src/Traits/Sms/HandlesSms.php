<?php

namespace Bryceandy\Beem\Traits\Sms;

use Bryceandy\Beem\Exceptions\ConfigurationUnavailableException;
use Bryceandy\Beem\Traits\MakesHttpRequests;
use Carbon\Carbon;
use Illuminate\Http\Client\Response;

trait HandlesSms
{
    use MakesHttpRequests;

    /**
     * @param string $message
     * @param array $recipients
     * @param string $source_addr
     *
     * @return Response
     *
     * @throws ConfigurationUnavailableException
     */
    public function sms(string $message, array $recipients, string $source_addr = 'INFO'): Response
    {
        return $this->call(
            'https://apisms.beem.africa/v1/send',
            'POST',
            array_merge(
                ['encoding' => 0],
                compact('message', 'recipients', 'source_addr')
            )
        );
    }

    /**
     * @param string $message
     * @param array $recipients
     * @param string $schedule
     * @param string $source_addr
     *
     * @return Response
     *
     * @throws ConfigurationUnavailableException
     */
    public function smsWithSchedule(
        string $message,
        array $recipients,
        string $schedule,
        string $source_addr = 'INFO'
    ): Response
    {
        $scheduledTime = Carbon::parse($schedule)->format('Y-m-d H:m');

        return $this->call(
            'https://apisms.beem.africa/v1/send',
            'POST',
            array_merge(
                ['encoding' => 0, 'schedule_time' => $scheduledTime],
                compact('message', 'recipients', 'source_addr')
            )
        );
    }

    /**
     * @return Response
     *
     * @throws ConfigurationUnavailableException
     */
    public function smsBalance(): Response
    {
        return $this->call(
            'https://apisms.beem.africa/public/v1/vendors/balance',
            'GET'
        );
    }

    /**
     * @param string|null $q
     * @param string|null $status
     * @return Response
     *
     * @throws ConfigurationUnavailableException
     */
    public function smsSenderNames(string $q = null, string $status = null): Response
    {
        $data = collect(compact('q', 'status'))
            ->filter(fn($datum) => ! is_null($datum))
            ->all();

        return $this->call(
            'https://apisms.beem.africa/public/v1/sender-names',
            'GET',
            $data
        );
    }
}