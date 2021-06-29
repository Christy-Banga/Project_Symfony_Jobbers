<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * Class AdminController
 * @package App\Controller\Admin
 */

class AdminController extends AbstractController
{
    /**
     * @return Response
     * @Route("/admin", name="admin_home")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/admin/category/ajout", name="category_ajout")
     */
    public function AddCategory(Request $request):Response
    {
        $category = new Category();
        $form =$this->createForm(CategoryType::class,$category)->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($category);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute("admin_home");
        }


        return $this->render('admin/category/ajout.html.twig', ["form"=>$form->createView()

        ]);
    }
}