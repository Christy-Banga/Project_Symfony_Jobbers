<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\Service;
use App\Form\ServiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="app_user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    /**
     * @param Request $request
     * @return Response
     * @Route("/user/service/ajout", name="user_service_ajout")
     */
    public function AddService(Request $request): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class,$service)->handleRequest($request);
        if( $form->isSubmitted()&& $form->isValid() ){
            $service->setUser($this->getUser());
            $service->setActive(false);

            $em=$this->getDoctrine()->getManager();
            $em->persist($service);
            $em->flush();
            return $this->redirectToRoute("app_user");

        }

        return $this->render('user/service/ajout.html.twig', [
            "form"=>$form->createView(),
        ]);
    }
}
