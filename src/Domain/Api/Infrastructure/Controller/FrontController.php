<?php

namespace App\Domain\Api\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends AbstractController
{
    /**
     * @param string $fileName
     * @return Response
     */
    public function archiveAction(string $fileName): Response
    {
        return $this->file("/tmp/graber/$fileName", $fileName);
    }
}