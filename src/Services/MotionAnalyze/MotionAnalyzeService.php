<?php

namespace App\Services\MotionAnalyze;

use App\Services\AbstractService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MotionAnalyzeService extends AbstractMotionAnalyzeService
{
    /** Declare variables */
    private ?string $negativeMotion = null;
    private ?string $neutrMotion = null;
    private ?string $positiveMotion = null;
    /** */


    /**
     * @throws TransportExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ServerExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getMotionAnalyze($data)
    {
        /** Get motions from NLP */
        $response = $this->getHttpClient()->request('POST', self::MOTION_ANALYZE_URL, [
            'json' => $data
        ]);
        $motionAnalyzeResponse = @json_decode($response->getContent(), true)["result"];
        /** */


        /** Set motions result */
        $this->negativeMotion = "%" . $motionAnalyzeResponse["neg"] * 100;
        $this->neutrMotion = "%" . $motionAnalyzeResponse["neu"] * 100;
        $this->positiveMotion = "%" . $motionAnalyzeResponse["pos"] * 100;
        /** */

        return @json_decode($response->getContent(), true)["result"];
    }



    public function getNegativeMotion(): string
    {
        return $this->negativeMotion;
    }


    public function getNeutrMotion(): string
    {
        return $this->neutrMotion;
    }


    public function getPositiveMotion(): string
    {
        return $this->positiveMotion;
    }
}