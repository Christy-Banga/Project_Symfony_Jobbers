<?php

namespace App\Controller;
use App\Entity\Image;
use App\Entity\User;
use App\Entity\Service;
use App\Form\Service1Type;
use App\Form\ServiceType;
use App\Form\UserPasswordType;
use App\Form\UserUpdateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{
    /**
     * @Route("/user", name="app_user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }


    /**
     * @param Request $request
     * @return Response
     * @Route("/user/service/ajout", name="user_service_ajout")
     */
    public function addService(Request $request): Response
    {
        $service = new Service();
        $form = $this->createForm(Service1Type::class, $service)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $service->setUser($this->getUser());
            //$service->setActive(true);
            $images = $form->get('images')->getData();
            foreach ($images as $image) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move($this->getParameter('images_directory'), $fichier);
                $img = new Image();
                $img->setName($fichier);
                $service->addImage($img);

            }


            $em = $this->getDoctrine()->getManager();
            $em->persist($service);
            $em->flush();
            return $this->redirectToRoute("app_user");

        }

        return $this->render('user/service/ajout.html.twig',
            ["form" => $form->createView(),
            ]);
    }


    /**
     * @param Request $request
     * @return Response
     * @Route("/user/service/modifier", name="user_service_modifier")
     */
    public function updateService(Request $request): Response
    {

        $user = $this->getUser();
        $form = $this->createForm(UserUpdateType::class, $user)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash("message", "profil mis a jour ");
            return $this->redirectToRoute("app_user");

        }

        return $this->render('user/updateProfil.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     * @Route("/user/password_modifier", name="password_modifier")
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($request->isMethod("POST")) {
            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();
            if($request->request->get('pass') == $request->request->get('pass2')){
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('pass')));
                $em->flush();
                $this->addFlash('message', 'Mot de passe mis à jour avec succès');

                return $this->redirectToRoute('app_user');
            }else{
                $this->addFlash('error', 'Les deux mots de passe ne sont pas identiques');
            }
        }


    return $this->render('user/updatePassword1.html.twig') ;  //,[ "form"=> $form->createView()]);

    }


    /**
     * @Route("/user/data", name="app_user_data")
     */
    public function Userdata(): Response
    {
        return $this->render('user/data.html.twig');
    }



}
