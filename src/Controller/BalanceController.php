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
        $totalStats = $categoryRepo->getTotalStats($user);
        $categories = array_flip(array_column($stats, 'name'));
        $years = array_keys(array_flip(array_column($stats, 'year')));
        
        foreach($categories as $category => $value) {
            $categories[$category] = [];
            foreach($years as $year) {
                $categories[$category][$year] = [];
            }
        }

        foreach($stats as $stat) {
            $categories[$stat["name"]][$stat["year"]][$stat["month"]] = $stat["amount"];
        }

        $monthsTotal = [];



        return $this->render('balance/index.html.twig', [
            'stats' => $categories,
            'totalStats' => $totalStats,
        ]);
    }
}
