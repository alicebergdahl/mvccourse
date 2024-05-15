<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Card\DeckOfCards;

class BankController extends AbstractController
{
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
}