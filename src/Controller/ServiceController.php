<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Service;
use App\Form\Service1Type;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/service")
 */
class ServiceController extends AbstractController
{
    /**
     * @Route("/", name="service_index", methods={"GET"})
     * @param ServiceRepository $serviceRepository
     * @return Response
     */
    public function index(ServiceRepository $serviceRepository): Response
    {
        return $this->render('service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
        ]);
    }


    /**
     * @Route("/new", name="service_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $service = new Service();
        $form = $this->createForm(Service1Type::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            foreach ($images as $image) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move($this->getParameter('images_directory'), $fichier);
                $img = new Image();
                $img->setName($fichier);
                $service->addImage($img);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($service);
            $entityManager->flush();

            return $this->redirectToRoute('service_index');
        }

        return $this->render('service/new.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="service_show", methods={"GET"})
     * @param Service $service
     * @return Response
     */
    public function show(Service $service): Response
    {
        return $this->render('service/show.html.twig', [
            'service' => $service,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="service_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Service $service
     * @return Response
     */
    public function edit(Request $request, Service $service): Response
    {
        $form = $this->createForm(Service1Type::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //recuperation du champ de l' image 'image' et recuperation des donneés getData
            $images = $form->get('images')->getData();
            // parcour du tableau ou est sont les images
            foreach ($images as $image) {
                //generer un nom aleatoire a chaque  image a l' aide de l'uniqid et  crypté par md5
                // ont donne une extention a  chaque  image grace  au guessExtension
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move($this->getParameter('images_directory'), $fichier);
                //ont  instancie la classe image pour  ajouté le nouveau nom du fichier a la base de donné
                $img = new Image;
                //ajout du nouveau nom a la base de donné
                $img->setName($fichier);
                //ajout de l' image au serve
                $service->addImage($img);

            }

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('service_index');
        }

        return $this->render('service/edit.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="service_delete", methods={"DELETE"})
     * @param Request $request
     * @param Service $service
     * @return Response
     */
    public function delete(Request $request, Service $service): Response
    {
        if ($this->isCsrfTokenValid('delete' . $service->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($service);
            $entityManager->flush();
        }

        return $this->redirectToRoute('service_index');
    }


    /**
     * @Route("delete/image/{id}", name="delete_image",methods={"DELETE"})
     * @param Request $request
     * @param Image $image
     * @return Response
     */
    public function detailService(Request $request, Image $image): Response
    {  //decode le contenue bruit et renvois un tableau associatif d' objet grace a true
        $data = json_decode($request->getContent(), true);
        //verification de la validité du token :_token  est un champs caché qui contient le token
        //connue par le site et l utilsateur cela permet d' identifié que c ' est l utilsateur qui
        // a bien effectué la requet car le token n' est connu que par lui et le site
        //ont verifie  si le token generer par  delete.id ex:delete.2 est equivaut au token dans le
        //champ _token
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
            $nom = $image->getName();
            unlink($this->getParameter('images_directory') . '/' . $nom);
            //suppression de l' image dans la base de donné
            $this->getDoctrine()->getManager()->remove($image);
            $this->getDoctrine()->getManager()->flush();
            //reponse en json
            return new JsonResponse(['success' => 1]);

        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }


    /**
     * @Route("/read/{slug}", name="service-read")
     * @param ServiceRepository $serviceRepository
     * @return Response
     */
    public function read($slug, ServiceRepository $serviceRepository): Response
    {
        $service = $serviceRepository->findOneBy(['slug' => $slug]);

        if (!$service){ throw new NotFoundHttpException('le service souhaite  n\'exite pas');}

        return $this->render('service/read.html.twig',['service'=>$service]);
    }
}