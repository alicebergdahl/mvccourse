<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MetricController extends AbstractController
{
    #[Route("/metric", name: "metric")]
    public function metric(): Response
    {

        return $this->render('metric/index.html.twig');
    }
}
