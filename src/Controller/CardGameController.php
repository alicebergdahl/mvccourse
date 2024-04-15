<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;

class CardGameController extends AbstractController
{
    #[Route("/card", name: "card")]
    public function index(): Response
    {
        return $this->render('card/home.html.twig');
    }

    #[Route("/card/deck", name: "card_deck")]
    public function showDeck(): Response
    {
        $deck = new DeckOfCards();
        $deck->sortCards();

        return $this->render('card/deck.html.twig', [
            'deck' => $deck,
        ]);
    }

    #[Route("/card/deck/shuffle", name: "card_deck_shuffle")]
    public function shuffleDeck(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();

        $session->set('deck', $deck);

        return $this->render('card/deck.html.twig', [
            'deck' => $deck,
        ]);
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

    #[Route("/card/deck/draw/{number}", name: "card_deck_draw")]
    public function drawCards($number, SessionInterface $session): Response
    {
        $deck = $session->get('deck');

        if (!$deck) {
            $deck = new DeckOfCards();
            $session->set('deck', $deck);
        }

        $drawnCards = $deck->deal($number)->getCards();
        $remainingCards = count($deck->getCards());

        $drawnCardsSession = $session->get('drawn_cards', []);

        foreach ($drawnCards as $card) {
            $drawnCardsSession[] = [
                'suit' => $this->mapSuit($card->getSuit()),
                'value' => $card->getValue()
            ];
        }

        $session->set('drawn_cards', $drawnCardsSession);

        $session->set('deck', $deck);

        return $this->render('card/draw.html.twig', [
            'drawnCards' => $drawnCards,
            'remainingCards' => $remainingCards,
        ]);
    }

    #[Route("/card/deck/draw", name: "card_deck_draw_one")]
    public function drawOneCard(SessionInterface $session): Response
    {
        return $this->redirectToRoute('card_deck_draw', ['number' => 1]);
    }
}
