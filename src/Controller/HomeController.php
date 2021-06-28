<?php

namespace App\Controller;

use App\Form\SearchServiceType;
use App\Repository\ServiceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     * @param ServiceRepository $serviceRepository
     * @return Response
     */
    public function index( ServiceRepository $serviceRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $donnees = $serviceRepository->findBy(['active'=>true],['created_at'=>'desc']);

        $form = $this->createForm(SearchServiceType::class);

        $search = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //On recherche les annonces correspondants aux mots clés
            $donnees = $serviceRepository->search(
                $search->get('mots')->getData(),
                $search->get('categorie')->getData()
            );
            
        }

        
        $serviceRepository = $paginator->paginate(
            $donnees,// On passe les données 
            $request->query->getInt('page',1), //Numero de la page en cours, 1 par défaut
            2
        );
        return $this->render('home/index.html.twig', [
            'services' => $serviceRepository,
            'form' => $form->createView()
        ]);
    }
}
