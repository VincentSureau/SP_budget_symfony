<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use DateTime;
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
        $months = array_keys(array_flip(array_column($stats, 'month')));
        
        foreach($categories as $category => $value) {
            $categories[$category] = [];
            foreach($months as $month){
                $category[$month] = 0;
            }
        }

        foreach($stats as $stat) {
            $categories[$stat["name"]][$stat["month"]] = $stat["amount"];
        }

        return $this->render('balance/index.html.twig', [
            'stats' => $categories,
            'totalStats' => $totalStats,
            'container_class' => 'container-fluid',
        ]);
    }
}
