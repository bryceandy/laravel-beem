<?php

namespace Bryceandy\Beem;

use Bryceandy\Beem\Traits\MakesHttpRequests;
use Carbon\Carbon;
use Illuminate\Http\Client\Response;

class Beem
{
    use MakesHttpRequests;

    /**
     * @param string $message
     * @param array $recipients
     * @param string $source_addr
     *
     * @return Response
     *
     * @throws Exceptions\ConfigurationUnavailableException
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
     * @throws Exceptions\ConfigurationUnavailableException
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
}