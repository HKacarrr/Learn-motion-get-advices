<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MotionAnalyzeController extends AbstractController
{
    #[Route('/', name: 'chat_ui', methods: ["GET"])]
    public function chatUI()
    {
        return $this->render('chat/chat.html.twig');
    }



    #[Route('/motion-analyze', name: 'motion_analyze', methods: ["POST"])]
    public function motionAnalyze(HttpClientInterface $httpClient, Request $request)
    {
        $payload = $request->request->all();
        $prompt = $payload["prompt"];


//        $data = ["I'm working and doing my master's degree, and I need to work at night to complete my work. But I also have to do my homework."];
        $data = [$prompt];
        $response = $httpClient->request('POST', 'http://localhost:5000/process-data', [
            'json' => $data,
        ]);



        $arrResponse = @json_decode($response->getContent(), true)["result"];
        $negative = "%" . $arrResponse["neg"] * 100;
        $neutral = "%" . $arrResponse["neu"] * 100;
        $positive = "%" . $arrResponse["pos"] * 100;

        $prompt = $data[0] . " How should a person who says, “What should I do?” What should I do? Can you advise me ?";


        $ch = curl_init();

        $payload = [
            "model" => "gemma:2b",
            "prompt" => $prompt,
            "stream" => false
        ];

        curl_setopt($ch, CURLOPT_URL, 'http://localhost:11434/api/generate');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        $aiResponse = json_decode($result, true);
        return new JsonResponse([
            "response" => $aiResponse["response"],
            "motions" => [
                "negative" => $negative,
                "neutral" => $neutral,
                "positive" => $positive
            ]
        ], 200);
    }
}