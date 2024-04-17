<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;

class GameController extends AbstractController
{
    #[Route("/game", name: "game")]
    public function index(): Response
    {
        return $this->render('game/home.html.twig');
    }

    #[Route("/game/start", name: "start_game")]
    public function startGame(Request $request): Response
    {
        $request->getSession()->remove('playerHand');
        $request->getSession()->remove('bankHand');

        return $this->render('game/play.html.twig', [
            'playerHand' => null,
            'bankHand' => null,
            'playerTotal' => 0,
            'gameOver' => false,
        ]);
    }
    
    #[Route("/game/draw", name: "draw_card", methods: ["GET", "POST"])]
    public function drawCard(Request $request): Response
    {

        $playerHand = $request->getSession()->get('playerHand', []);

        $deck = new DeckOfCards();
        $deck->shuffle();

        $newCard = $deck->deal(1)->getCards()[0];

        $playerHand[] = $newCard;

        $playerTotal = 0;
        foreach ($playerHand as $card) {
            $value = $card->getValue();
            if ($value === 'A') {
                $playerTotal += 1;
            } elseif (in_array($value, ['K', 'Q', 'J'])) {
                $playerTotal += 10;
            } else {
                $playerTotal += intval($value);
            }
        }

        $request->getSession()->set('playerHand', $playerHand);

        if ($playerTotal > 21) {
            return $this->render('game/play.html.twig', [
                'playerHand' => $playerHand,
                'bankHand' => null,
                'playerTotal' => $playerTotal,
                'gameOver' => true,
                'winMessage' => 'Game over'
            ]);
        }
    
        return $this->render('game/play.html.twig', [
            'playerHand' => $playerHand,
            'bankHand' => null,
            'playerTotal' => $playerTotal,
            'gameOver' => false,
        ]);
    }

    #[Route("/game/doc", name: "game_documentation")]
    public function documentation(): Response
    {
        return $this->render('game/documentation.html.twig');
    }

    #[Route("/game/stand", name: "stand_game", methods: ["POST"])]
    public function standGame(Request $request): Response
    {
        $playerHand = $request->getSession()->get('playerHand', []);

        $deck = new DeckOfCards();
        $deck->shuffle();

        $bankHand = $request->getSession()->get('bankHand', []);

        while ($this->calculateHandValue($bankHand) < 17) {
            $newCard = $deck->deal(1)->getCards()[0];
            $bankHand[] = $newCard;
        }

        $request->getSession()->set('bankHand', $bankHand);

        $playerTotal = $this->calculateHandValue($playerHand);
        $bankTotal = $this->calculateHandValue($bankHand);
    
        if ($bankTotal > 21) {
            return $this->render('game/play.html.twig', [
                'playerHand' => $playerHand,
                'bankHand' => $bankHand,
                'playerTotal' => $playerTotal,
                'bankTotal' => $bankTotal,
                'gameOver' => true,
                'winMessage' => 'Du vann!',
            ]);
        }

        if ($playerTotal > $bankTotal) {
            return $this->render('game/play.html.twig', [
                'playerHand' => $playerHand,
                'bankHand' => $bankHand,
                'playerTotal' => $playerTotal,
                'bankTotal' => $bankTotal,
                'gameOver' => true,
                'winMessage' => 'Du vann!',
            ]);
        } elseif ($playerTotal < $bankTotal) {
            return $this->render('game/play.html.twig', [
                'playerHand' => $playerHand,
                'bankHand' => $bankHand,
                'playerTotal' => $playerTotal,
                'bankTotal' => $bankTotal,
                'gameOver' => true,
                'winMessage' => 'Game over',
            ]);
        } else {
            return $this->render('game/play.html.twig', [
                'playerHand' => $playerHand,
                'bankHand' => $bankHand,
                'playerTotal' => $playerTotal,
                'bankTotal' => $bankTotal,
                'gameOver' => true,
                'winMessage' => 'Banken vann!',
            ]);
        }
    }
    
    /**
     * Calculates the total value of a hand.
     *
     * @param Card[] $hand The array representing the hand of cards.
     * @return int The total value of the hand.
     */
    private function calculateHandValue(array $hand): int
    {
        $totalValue = 0;
        foreach ($hand as $card) {
            $value = $card->getValue();
            if ($value === 'A') {
                $totalValue += 1;
            } elseif (in_array($value, ['K', 'Q', 'J'])) {
                $totalValue += 10;
            } else {
                $totalValue += intval($value);
            }
        }
        return $totalValue;
    }

    #[Route("/api/game", name: "game_state")]
    public function getGameState(Request $request): JsonResponse
    {
        $playerHand = (array) $request->getSession()->get('playerHand', []);
        $bankHand = (array) $request->getSession()->get('bankHand', []);

        $playerTotal = $this->calculateHandValue($playerHand);
        $bankTotal = $this->calculateHandValue($bankHand);

        $gameOver = $playerTotal > 21 || count($bankHand) >= 2;

        if ($gameOver) {
            if ($playerTotal > 21) {
                $winMessage = 'Banken vann!';
            } elseif ($bankTotal > 21 || $playerTotal > $bankTotal) {
                $winMessage = 'Du vann!';
            } else {
                $winMessage = 'Game over';
            }
        } else {
            $winMessage = null;
        }

        $data = [
            'player_total' => $playerTotal,
            'bank_total' => $bankTotal,
            'game_over' => $gameOver,
            'win_message' => $winMessage
        ];

        return new JsonResponse($data);
    }
}