<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BalanceController extends AbstractController
{
    /**
     * @Route("/bilan", name="balance")
     */
    public function index(CategoryRepository $categoryRepo)
    {
        $user = $this->getUser();
        $stats = $categoryRepo->getStats($user);
        dd($stats);
        return $this->render('balance/index.html.twig', [
            'stats' => $stats
        ]);
    }
}
