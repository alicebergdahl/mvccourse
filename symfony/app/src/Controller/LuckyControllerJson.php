<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerJson extends AbstractController
{
    #[Route("/api", name: "api_landing_page")]
    public function landingPage(): Response
    {
        $routes = [
            'quote' => '/api/quote',
            'deck' => '/api/deck',
        ];

        return $this->render('landing_page.html.twig', [
            'routes' => $routes
        ]);
    }

    #[Route("/api/quote", name: "api_quote")]
    public function getQuote(): Response
    {
        $quotes = [
            'Inside out',
            'Pumped Up Kicks',
            'White Flag',
            'Let Me Blow Ya Mind',
            'Heartbeats',
            'Hurtful',
            'Boadicea',
            'The Lost Song',
            'Come Meh Way',
            'Kiss',
            'Losing You',
            'See You All',
            'Waiting On the World to Change',
            'Cool Cat',
            'Mistadobalina',
            'Oh Boy',
            'Everyday Normal Guy 2',
            'Lucy Pearls Way',
            'Public SService Announcement (Interlude)',
            'Never Forget You',

        ];

        $randomQuote = $quotes[array_rand($quotes)];

        $data = [
            'song' => $randomQuote,
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
