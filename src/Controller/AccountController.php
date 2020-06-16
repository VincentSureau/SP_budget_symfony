<?php

namespace App\Controller;

use App\Form\FilterOperationType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    /**
     * @Route("/mon-compte", name="account")
     */
    public function index(Request $request, CategoryRepository $categoryRepository)
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
        
        $form = $this->createForm(FilterOperationType::class);
        $form->handleRequest($request);

        $dateEnd = new \DateTime('now');
        $dateStart = new \DateTime('first day of this month');
        
        if ($form->isSubmitted() && $form->isValid()) {
            $dateStart = $form->get('dateStart')->getData();
            $dateEnd = $form->get('dateEnd')->getData();
        }
        
        
        $categories = $categoryRepository->getCategoriesWithOperations($user, $dateStart, $dateEnd);

        return $this->render('account/index.html.twig', [
            'categories' => $categories,
            'form' => $form->createView()
        ]);
    }
}
