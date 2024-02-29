<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\RecType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FrontHomeController extends AbstractController
{
    #[Route('/front/home', name: 'app_front_home')]
    public function index(): Response
    {
        return $this->render('front_home/index.html.twig', [
            'controller_name' => 'FrontHomeController',
        ]);
    }





  #le s4 elements du crud juste affichage
  #crud fel front 
  #recruteur //// reclamation 
  #back affichage et supprission details
 
    #[Route('/front/create', name: 'create')]
     public function create(Request $request)
   {

        $reclamation = new Reclamation();
        $form = $this->createform(RecType::class);
        $form-> handleRequest($request);

      return $this->render('rec/create.html.twig',[
        $reclamation= $this->$form->createView()]);
    }

    #[Route('/front/update', name: 'update')]
    public function update(Request $request, int $idrec)
{   
    $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($idrec);
    $form = $this->createform(RecType::class, $reclamation);
    $form->handleRequest($request);
    $formView = $form->createView();
    if($form->isSubmitted() && $form->isValid()){
       $em = $this->getDoctrine()->getManager();
       $em->persist($reclamation);
       $em->flush();

      
       return $this->redirectToRoute('app_rec');
    }
    return $this->render('rec/update.html.twig',[
       'form' => $formView,
   ]);
}
}
