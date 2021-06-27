<?php

namespace App\Controller;

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
        $serviceRepository = $paginator->paginate(
            $donnees,// On passe les données 
            $request->query->getInt('page',1), //Numero de la page en cours, 1 par défaut
            1
        );
        return $this->render('home/index.html.twig', [
            'services' => $serviceRepository
        ]);
    }
}
