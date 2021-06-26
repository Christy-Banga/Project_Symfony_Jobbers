<?php


namespace App\Controller\Admin;


use App\Entity\Category;
use App\Entity\Service;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{


    /**
     * @Route("/admin/services", name="admin_services")
     * @param ServiceRepository $serviceRepository
     * @return Response
     */
    public function index(ServiceRepository $serviceRepository): Response
    {
        return $this->render('admin/service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
        ]);
    }



    /**
     * @Route("/admin/services/activer/{id}", name="admin_services_activer")
     * @param Service $service
     * @param Service $service
     * @return Response
     */
    public function activate(Service $service):Response
    {
        $service->setActive(($service->getActive())?false:true);
        $em =$this->getDoctrine()->getManager();
        $em->persist($service );
        $em->flush();
        return new Response("true");

    }


    /**
     * @Route("/admin/services/supprimer/{id}", name="admin_services_supprimer")
     * @param Service $service
     * @return Response
     */
    public function remove(Service $service):Response
    {
        //$service->setActive(($service->getActive())?false:true);
        $em =$this->getDoctrine()->getManager();
        $em->remove($service );
        $em->flush();
        $this->addFlash('message', 'suppression effectuée avec succès');
        return $this->redirectToRoute("admin_services");

    }

}