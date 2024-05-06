<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Book;
use Doctrine\Persistence\ManagerRegistry;

class LuckyControllerJson extends AbstractController
{
    #[Route("/api", name: "api_landing_page")]
    public function landingPage(): Response
    {
        $routes = [
            'quote' => '/api/quote',
            'deck' => '/api/deck',
            'draw' => '/api/deck/draw',
            'shuffle' => '/api/deck/shuffle',
            'game_state' => '/api/game',
            'all_books' => '/api/library/books',
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

    #[Route("/api/library/books", name: "api_library_books")]
    public function getAllBooks(ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $books = $entityManager->getRepository(Book::class)->findAll();

        $bookArray = array_map(function ($book) {
            return [
                'id' => $book->getId(),
                'name' => $book->getName(),
                'author' => $book->getAuthor(),
                'ISBN' => $book->getISBN(),
                'image' => $book->getImage(),
            ];
        }, $books);

        $response = new JsonResponse($bookArray);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/library/book/{isbn}", name: "api_library_book_by_isbn")]
    public function getBookByIsbn(ManagerRegistry $doctrine, string $isbn): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->findOneBy(['ISBN' => $isbn]);

        if (!$book) {
            return new JsonResponse(['error' => 'Book not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $bookData = [
            'id' => $book->getId(),
            'name' => $book->getName(),
            'author' => $book->getAuthor(),
            'ISBN' => $book->getISBN(),
            'image' => $book->getImage(),
        ];

        return new JsonResponse($bookData, JsonResponse::HTTP_OK);
    }
}
