<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerTwig extends AbstractController
{
    #[Route("/", name: "home")]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route("/report", name: "report")]
    public function report(Request $request): Response
    {
        $fragment = $request->get('_fragment');

        return $this->render('report.html.twig', ['fragment' => $fragment]);
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route("/lucky", name: "lucky")]
    public function lucky(): Response
    {
        $number = random_int(0, 100);
        $images = [
            'img1' => 'image1.JPG',
            'img2' => 'image2.JPG',
            'img3' => 'image3.JPG',
            'img4' => 'image4.JPG',
            'img5' => 'image5.JPG',
            'img6' => 'image6.JPG',
            'img7' => 'image7.JPG',
            'img8' => 'image8.JPG',
            'img9' => 'image9.JPG',
            'img10' => 'image10.JPG',
        ];

        $randomImage = $images[array_rand($images)];

        return $this->render('lucky.html.twig', [
            'number' => $number,
            'randomImage' => $randomImage
        ]);
    }

}
