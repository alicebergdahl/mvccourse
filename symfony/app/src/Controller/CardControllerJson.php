<?php

namespace App\Controller;

use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CardControllerJson extends AbstractController
{
    #[Route("/api/deck", name: "api_deck")]
    public function getSortedDeck(): JsonResponse
    {
        $deck = new DeckOfCards();
        $deck->sortCards();

        $cards = array_map(function ($card) {
            $suit = $this->mapSuit($card->getSuit());
            return [
                'suit' => $suit,
                'value' => $card->getValue()
            ];
        }, $deck->getCards());

        return new JsonResponse($cards);
    }

    private function mapSuit($unicodeSuit): string
    {
        switch ($unicodeSuit) {
            case '♥':
                return 'hearts';
            case '♦':
                return 'diamonds';
            case '♣':
                return 'clubs';
            case '♠':
                return 'spades';
            default:
                return '';
        }
    }

    #[Route("/api/deck/shuffle", name: "api_deck_shuffle", methods: ["GET", "POST"])]
    public function shuffleDeck(SessionInterface $session): JsonResponse
    {
        $deck = $session->get('deck');

        if (!$deck) {
            $deck = new DeckOfCards();
        }

        $deck->shuffle();

        $shuffledCards = $session->get('api_shuffled_cards', []);

        foreach ($deck->getCards() as $card) {
            $shuffledCards[] = [
                'suit' => $this->mapSuit($card->getSuit()),
                'value' => $card->getValue()
            ];
        }

        $session->set('api_shuffled_cards', $shuffledCards);

        return new JsonResponse($shuffledCards);
    }

    #[Route("/api/deck/draw", name: "api_deck_draw_one", methods: ["GET", "POST"])]
    public function drawOneCard(SessionInterface $session): JsonResponse
    {
        return $this->drawCards(1, $session);
    }

    #[Route("/api/deck/draw/{number}", name: "api_deck_draw", methods: ["GET", "POST"])]
    public function drawCards(int $number, SessionInterface $session): JsonResponse
    {
        $deck = $session->get('deck');

        if (!$deck) {
            $deck = new DeckOfCards();
            $session->set('deck', $deck);
        }

        $drawnCards = $deck->deal($number)->getCards();
        $remainingCards = count($deck->getCards());

        $drawnCardsSession = $session->get('api_drawn_cards', []);

        foreach ($drawnCards as $card) {
            $drawnCardsSession[] = [
                'suit' => $this->mapSuit($card->getSuit()),
                'value' => $card->getValue()
            ];
        }

        $session->set('api_drawn_cards', $drawnCardsSession);
        $session->set('deck', $deck);

        $response = [
            'drawnCards' => $drawnCardsSession,
            'remainingCards' => $remainingCards,
        ];

        return new JsonResponse($response);
    }
}
