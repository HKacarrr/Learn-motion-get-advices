<?php

namespace App\Services\AdviceGenerator;

use App\Services\AbstractService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class AdviceGeneratorService extends AbstractAdviceGeneratorService
{
    /** Declare variables */
    private ?string $prompt = null;
    /** */



    /** Getter & Setters */
    public function setPrompt(string $prompt): static
    {
        $this->prompt = $prompt;
        return $this;
    }


    protected function getPrompt(): string
    {
        return $this->prompt;
    }
    /** */


    /**
     * @throws TransportExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ServerExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getAdvices()
    {
        $payload = $this->payloadGenerator($this->getPrompt());
        $result = $this->getHttpClient()->request('POST', self::ADVICE_URL, [
            'json' => $payload
        ]);
        return json_decode($result->getContent(), true);
    }
}