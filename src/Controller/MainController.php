<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class MainController extends AbstractController
{
    /**
     * @return Response
     */
    public function action(): Response
    {
        return $this->render('index.html.twig');
    }
}