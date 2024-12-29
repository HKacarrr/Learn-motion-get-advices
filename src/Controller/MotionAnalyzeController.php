<?php

namespace App\Controller;

use App\Services\AdviceGenerator\AdviceGeneratorService;
use App\Services\MotionAnalyze\MotionAnalyzeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MotionAnalyzeController extends AbstractController
{
    #[Route('/', name: 'chat_ui', methods: ["GET"])]
    public function chatUI()
    {
        return $this->render('chat/chat.html.twig');
    }


    /**
     * @throws NotFoundExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/motion-analyze', name: 'motion_analyze', methods: ["POST"])]
    public function motionAnalyze(Request $request, MotionAnalyzeService $motionAnalyzeService, AdviceGeneratorService $adviceGeneratorService): JsonResponse
    {
        $payload = $request->request->all();
        $prompt = $payload["prompt"];

        $data = [$prompt];
        $prompt = $data[0] . " How should a person who says, “What should I do?” What should I do? Can you advise me ?";

        $motionAnalyzeService->getMotionAnalyze($data);
        $aiResponse = $adviceGeneratorService
            ->setPrompt($prompt)
            ->getAdvices();

        return new JsonResponse([
            "response" => $aiResponse["response"],
            "motions" => [
                "negative" => $motionAnalyzeService->getNegativeMotion(),
                "neutral" => $motionAnalyzeService->getNeutrMotion(),
                "positive" => $motionAnalyzeService->getPositiveMotion()
            ]
        ], 200);
    }
}