<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @return Response
     * @Route("/message", name="app_message")
     */

    public function index(): Response
    {
        return $this->render('message/index.html.twig');
    }



    /**
     * @Route("/send",name="app_send")
     * @return Response
     * @param Request $request
     */
    public function send(Request $request):Response
    {

        $message = new Message();
        $form = $this->createForm(MessageType::class,$message)->handleRequest($request);
        if( $form->isSubmitted()&& $form->isValid()){
            $message->setSender($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
            $this->addFlash('message','Message ');
            return $this->redirectToRoute("app_message");

        }
        return $this->render("message/send.html.twig",['form'=>$form->createView()]);
    }


    /**
     * @Route("/received", name="app_received")
     * @return Response
     */
    public function received(): Response
    {
        return $this->render('message/received.html.twig');
    }

    /**
     * @Route ("/read/{id}",name="app_read")
     * @return Response
     * @param Message $message
     */
    public function read(Message $message): Response
    {
        $message->setIsRead(true);
        $em=$this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();

        return $this->render('message/read.html.twig',['message'=>$message]);
    }


    /**
     * @Route("/messageSent",name="app_messageSent")
     * @param Request $request
     * @return Response
     */
    public function messageSent(Request $request):Response
    {
        return $this->render("message/messageSent.html.twig");
    }



    /**
     * @Route ("/delete/{id}",name="app_delete")
     * @return Response
     * @param Message $message
     */
    public function delete(Message $message): Response
    {

        $em=$this->getDoctrine()->getManager();
        $em->remove($message);
        $em->flush();

        return $this->redirectToRoute("app_received");
    }




}
