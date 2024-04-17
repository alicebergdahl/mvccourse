<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;

class SessionDebugController extends AbstractController
{
    #[Route("/session", name: "session")]
    public function index(SessionInterface $session): Response
    {
        return $this->render('session.html.twig', [
            'session' => $session->all(),
        ]);
    }

    #[Route("/session/delete", name: "session_delete")]
    public function delete(SessionInterface $session, Request $request): Response
    {
        $session->clear();

        $this->addFlash('success', 'Sessionen har raderats.');

        return $this->redirectToRoute('card');
    }
}
