<?php

namespace App\Controller;

use App\Repository\LevelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LevelInfoController extends AbstractController
{
    #[Route('/list/levelinfo', name: 'info', methods: ['GET'])]
    public function levelinfo(LevelRepository $levelRepository): Response
    {
        $level = $levelRepository->findAll();
        return $this->render('list/levelinfo.html.twig', ['list' => $level]);
    }
}
