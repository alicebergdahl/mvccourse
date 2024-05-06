<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Book;
use App\Form\BookType;

class LibraryController extends AbstractController
{
    #[Route('/library', name: 'library_home')]
    public function listBooks(EntityManagerInterface $entityManager): Response
    {
        $bookRepository = $entityManager->getRepository(Book::class);
        $books = $bookRepository->findAll();

        return $this->render('library/list.html.twig', [
            'books' => $books
        ]);
    }

    #[Route('/library/seed', name: 'seed_books')]
    public function seedBooks(EntityManagerInterface $entityManager): Response
    {
        $bookData = [
            ['name' => 'Alice', 'author' => 'Alice Bergdahl', 'ISBN' => 9780141036144, 'image' => 'alice.png'],
        ];

        foreach ($bookData as $data) {
            $book = new Book();
            $book->setName($data['name']);
            $book->setAuthor($data['author']);
            $book->setISBN($data['ISBN']);
            $book->setImage($data['image']);
            $entityManager->persist($book);
        }

        $entityManager->flush();

        return new Response("Books have been added to the library!");
    }

    #[Route('/library/add', name: 'add_book')]
    public function addBook(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('library_home');
        }

        return $this->render('library/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/library/book/{id}', name: 'view_book', methods: ['GET'])]
    public function viewBook(Book $book): Response
    {

        return $this->render('library/view.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/library/edit/{id}', name: 'edit_book')]
    public function editBook(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException('No book found for id '.$id);
        }

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('view_book', ['id' => $book->getId()]);
        }

        return $this->render('library/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/library/delete/{id}', name: 'delete_book')]
    public function deleteBook(EntityManagerInterface $entityManager, int $id): Response
    {
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException('No book found for id ' . $id);
        }

        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('library_home');
    }
}
