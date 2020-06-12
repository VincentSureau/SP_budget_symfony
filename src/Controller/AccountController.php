<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    /**
     * @Route("/mon-compte", name="account")
     */
    public function index(CategoryRepository $categoryRepository)
    {
        /**
         * Avec la méthode par défaut de doctrine, les opérations ne sont pas jointe automatiquement
         * et il y a donc une requête supplémentaire à chaques fois qu'on utilise categorie.operation
         * dans twig (problème N+1)
         * en utilisant notre propre requête, on peut joindre les opérations directement
         * et donc supprimer 9 requêtes inutiles
         */
        // $categories = $categoryRepository->findAll();
        $user = $this->getUser();
        $categories = $categoryRepository->getCategoriesWithOperations($user);
        
        return $this->render('account/index.html.twig', [
            'categories' => $categories,
        ]);
    }
}
