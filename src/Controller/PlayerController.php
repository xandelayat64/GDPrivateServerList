<?php

namespace App\Controller;

use App\Entity\Level;
use App\Entity\Player;
use App\Form\PlayerType;
use App\Repository\LevelRepository;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PlayerController extends AbstractController
{
    #[Route('/player', name: 'player', methods: ['GET'])]
    public function player(PlayerRepository $playerRepository): Response
    {
        $player = $playerRepository->findAll();
        return $this->render('player/player.html.twig', ['player' => $player]);
    }

    #[Route('/player/new', name: 'player_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, PlayerRepository $playerRepository): Response
    {
        $player = new Player();
        $form = $this->createForm(PlayerType::class,$player);
        /*
        $form = $this->createFormBuilder($player)
            ->add('name', TextType::class)
            ->add('submit', SubmitType::class, array('label' => 'Add player'))
            ->getForm();
        */

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $playerRepository->save($player, true);

            return $this->redirectToRoute('player');
        }
        return $this->render('player/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/player/edit/{id}', name: 'player_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Player $player, EntityManagerInterface $entityManager, PlayerRepository $playerRepository): RedirectResponse|Response
    {
        /*
        $form = $this->createFormBuilder($player)
            ->add('name', TextType::class)
            ->add('completions', TextType::class)
            ->add('submit', SubmitType::class, array('label' => 'Update'))
            ->getForm();
        */
        $player = new Player();
        $form = $this->createForm(PlayerType::class,$player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('player');
        }

        return $this->render('player/edit.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/player/delete/{id}', name: 'player_delete', methods: ['POST'])]
    public function delete(Request $request, Player $player, PlayerRepository $playerRepository): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete'.$player->getId(), $request->request->get('_token'))) {
            $playerRepository->remove($player, true);
        }

        return $this->redirectToRoute('player');
    }
}
