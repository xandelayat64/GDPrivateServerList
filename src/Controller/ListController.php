<?php

namespace App\Controller;

use App\Entity\Level;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LevelRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Form\LevelType;

class ListController extends AbstractController
{
    // Tab for points assigned to levels
    private const POINTS_TABLE = [
        1 => 350, 2 => 332, 3 => 313, 4 => 298, 5 => 272,
        6 => 254, 7 => 237, 8 => 221, 9 => 207, 10 => 195,
        11 => 183, 12 => 172, 13 => 162, 14 => 153, 15 => 145,
        16 => 137, 17 => 130, 18 => 124, 19 => 118, 20 => 112,
        21 => 111, 22 => 109, 23 => 108, 24 => 107, 25 => 105,
        26 => 104, 27 => 103, 28 => 101, 29 => 100, 30 => 99,
        31 => 97, 32 => 96, 33 => 95, 34 => 93, 35 => 92,
        36 => 90, 37 => 88, 38 => 86, 39 => 84, 40 => 82,
        41 => 80, 42 => 78, 43 => 76, 44 => 74, 45 => 73,
        46 => 71, 47 => 69, 48 => 68, 49 => 66, 50 => 65,
        51 => 64, 52 => 62, 53 => 61, 54 => 60, 55 => 59,
        56 => 58, 57 => 57, 58 => 56, 59 => 55, 60 => 54,
        61 => 53, 62 => 52, 63 => 51, 64 => 50, 65 => 49,
        66 => 48, 67 => 47, 68 => 46, 69 => 45, 70 => 44,
        71 => 43, 72 => 42, 73 => 41, 74 => 38, 75 => 37,
        76 => 36, 77 => 36, 78 => 36, 79 => 35, 80 => 35,
        81 => 34, 82 => 34, 83 => 34, 84 => 34, 85 => 33,
        86 => 33, 87 => 32, 88 => 31, 89 => 31, 90 => 31,
        91 => 30, 92 => 30, 93 => 30, 94 => 29, 95 => 29,
        96 => 28, 97 => 28, 98 => 27, 99 => 26, 100 => 25,
        101 => 24, 102 => 24, 103 => 23, 104 => 23, 105 => 23,
        106 => 22, 107 => 22, 108 => 21, 109 => 21, 110 => 20,
        111 => 20, 112 => 20, 113 => 19, 114 => 19, 115 => 18,
        116 => 18, 117 => 18, 118 => 17, 119 => 17, 120 => 16,
        121 => 16, 122 => 16, 123 => 15, 124 => 15, 125 => 15,
        126 => 14, 127 => 14, 128 => 14, 129 => 13, 130 => 13,
        131 => 12, 132 => 12, 133 => 12, 134 => 12, 135 => 11,
        136 => 11, 137 => 11, 138 => 10, 139 => 10, 140 => 9,
        141 => 8, 142 => 7, 143 => 7, 144 => 6, 145 => 6,
        146 => 5, 147 => 4, 148 => 3, 149 => 2, 150 => 2
    ];

    #[Route('/list', name: 'list', methods: ['GET'])]
    public function list(LevelRepository $levelRepository): Response
    {
        $levels = $levelRepository->findBy([], ['place' => 'ASC']);

        return $this->render('list/list.html.twig', ['list' => $levels]);
    }

    #[Route('/list/new', name: 'list_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, LevelRepository $levelRepository): Response
    {
        $level = new Level();
        $form = $this->createForm(LevelType::class, $level);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $level = $form->getData();

            $place = max(1, min(150, $level->getPlace()));
            $level->setPlace($place);

            $this->assignPoints($level);

            $this->shiftLevelsDown($entityManager, $levelRepository, $place);

            $levelRepository->save($level, true);

            return $this->redirectToRoute('list');
        }

        return $this->render('list/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/list/edit/{id}', name: 'list_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Level $level, EntityManagerInterface $entityManager, LevelRepository $levelRepository): RedirectResponse|Response
    {
        $oldPlace = $level->getPlace();

        $form = $this->createForm(LevelType::class, $level);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $level = $form->getData();
            $newPlace = max(1, min(150, $level->getPlace()));

            if ($oldPlace !== $newPlace) {
                if ($newPlace < $oldPlace) {
                    $this->shiftLevelsInRange($entityManager, $levelRepository, $newPlace, $oldPlace - 1, 1);
                } else {
                    $this->shiftLevelsInRange($entityManager, $levelRepository, $oldPlace + 1, $newPlace, -1);
                }

                $level->setPlace($newPlace);
            }

            $this->assignPoints($level);

            $levelRepository->save($level, true);

            return $this->redirectToRoute('list');
        }

        return $this->render('list/edit.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/list/delete/{id}', name: 'list_delete', methods: ['POST'])]
    public function delete(Request $request, Level $level, EntityManagerInterface $entityManager, LevelRepository $levelRepository): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete'.$level->getId(), $request->request->get('_token'))) {
            $deletedPlace = $level->getPlace();

            $levelRepository->remove($level, true);

            $this->shiftLevelsUp($entityManager, $levelRepository, $deletedPlace);
        }

        return $this->redirectToRoute('list');
    }

    /**
     * Give points according to the place
     */
    private function assignPoints(Level $level): void
    {
        $place = $level->getPlace();
        $points = self::POINTS_TABLE[$place] ?? 0;
        $level->setPoints($points);
    }

    private function shiftLevelsDown(EntityManagerInterface $entityManager, LevelRepository $levelRepository, int $fromPlace): void
    {
        $levels = $levelRepository->createQueryBuilder('l')
            ->where('l.place >= :place')
            ->setParameter('place', $fromPlace)
            ->orderBy('l.place', 'DESC')
            ->getQuery()
            ->getResult();

        foreach ($levels as $level) {
            $newPlace = $level->getPlace() + 1;
            $level->setPlace($newPlace);
            $this->assignPoints($level);
        }

        $entityManager->flush();
    }

    private function shiftLevelsUp(EntityManagerInterface $entityManager, LevelRepository $levelRepository, int $fromPlace): void
    {
        $levels = $levelRepository->createQueryBuilder('l')
            ->where('l.place > :place')
            ->setParameter('place', $fromPlace)
            ->orderBy('l.place', 'ASC')
            ->getQuery()
            ->getResult();

        foreach ($levels as $level) {
            $newPlace = $level->getPlace() - 1;
            $level->setPlace($newPlace);
            $this->assignPoints($level);
        }

        $entityManager->flush();
    }

    private function shiftLevelsInRange(EntityManagerInterface $entityManager, LevelRepository $levelRepository, int $start, int $end, int $offset): void
    {
        $order = $offset > 0 ? 'DESC' : 'ASC';

        $levels = $levelRepository->createQueryBuilder('l')
            ->where('l.place >= :start')
            ->andWhere('l.place <= :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('l.place', $order)
            ->getQuery()
            ->getResult();

        foreach ($levels as $level) {
            $newPlace = $level->getPlace() + $offset;
            $level->setPlace($newPlace);
            $this->assignPoints($level);
        }

        $entityManager->flush();
    }
}
