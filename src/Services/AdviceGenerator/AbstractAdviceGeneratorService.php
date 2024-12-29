<?php

namespace App\Services\AdviceGenerator;

use App\Services\AbstractService;

abstract class AbstractAdviceGeneratorService extends AbstractService
{
    /** Constants  */
    const MODEL = "gemma:2b";
    const STREAM = false;
    const ADVICE_URL = "http://localhost:11434/api/generate";
    /** */


    protected function payloadGenerator($prompt): array
    {
        return [
            "model" => self::MODEL,
            "prompt" => $prompt,
            "stream" => self::STREAM
        ];
    }
}