<?php


namespace App\Controller\Admin;


use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;




/**
 * Class CategoryController
 * @package App\Controller\Admin
 */
class CategoryController extends AbstractController
{

    /**
     * @Route("/admin/category", name="admin_category")
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/category/ajout", name="category_ajout")
     * @param Request $request
     * @return Response
     */
    public function addCategory(Request $request):Response
    {
        $category = new Category();
        $form =$this->createForm(CategoryType::class,$category)->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($category);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute("admin_category");
        }


        return $this->render('admin/category/ajout.html.twig', ["form"=>$form->createView()

        ]);
    }
//     * //@ParamConverter("category", options={"id" = "category_id"})
    /**
     * @Route("/admin/category/modifier/{id}", name="category_modifier")
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    public function updateCategory( Category $category,Request $request):Response
    {
        $form =$this->createForm(CategoryType::class,$category)->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()) {
            //$this->getDoctrine()->getManager()->persist($category);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute("admin_category",["id" =>$category->getId()]);
        }


     return $this->render('admin/category/modifier.html.twig', ["form" => $form->createView()
     ]);
    }



   // @Route("/admin/category/supprimer/{category_id}", name="category_supprimer")
    //@ParamConverter("service", options={"id" = "category_id"})
    //@Route("/admin/category/supprimer/{id}", name="category_supprimer")
    // @Entity("category", expr="repository.find(category_id)")


    /**
     * @Route("/admin/category/{id}/supprimer",name="category_supprimer")
     * @param Category $category
     * @return RedirectResponse
     */
    public function remove(Category $category):RedirectResponse
    {

        $em =$this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        $this->addFlash('message', 'suppression effectuée avec succès');
        return $this->redirectToRoute("admin_category");

    }


}