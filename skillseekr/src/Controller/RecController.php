<?php
#crud lena 
namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\RecType;
use Doctrine\DBAL\Types\TextType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\Length;

class RecController extends AbstractController
{
    #[Route('/rec', name: 'app_rec')]
    public function index(): Response
    {$data = $this->getDoctrine()->getRepository(Reclamation::class)->findAll();
        
        return $this->render('rec/index.html.twig', [
            'controller_name' => 'RecController',
            'list'=>$data
        ]);
    }
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
   
    #[Route('/rec/create', name: 'create')]
    public function create(Request $request)
    {
      $reclamation = new Reclamation();
      $form = $this->createForm(RecType::class, $reclamation);
      $formView = $form->createView();
    #  $form = $this->createForm(RecType::class, $reclamation, [
   #     'constraints' => [
   #        new Length(['min' => 10]),
  #      ] 
    # ]);
     $form->handleRequest($request);
     if($form->isSubmitted() && $form->isValid()){
        $em = $this->getDoctrine()->getManager();
        $em->persist($reclamation);
        $em->flush();

        return $this->redirectToRoute('app_rec');
     }
     return $this->render('rec/create.html.twig',[
        'form' => $formView,
    ]);
    }

    #[Route('/rec/update{idrec}', name: 'update')]
    public function update (Request $request,$idrec)
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

#[Route('/rec/delete{idrec}', name: 'delete')]
public function delete($idrec){
    $data = $this->getDoctrine()->getRepository(Reclamation::class)->find($idrec);
    $em = $this->getDoctrine()->getManager();
    $em->remove($data);
    $em->flush();

    return $this->redirectToRoute('app_rec');
}

#[Route('/backrec', name: 'app_backrec')]
    public function indexBack(): Response
    {$data = $this->getDoctrine()->getRepository(Reclamation::class)->findAll();
        
        return $this->render('back_home/backrec.html.twig', [
            'controller_name' => 'RecController',
            'list'=>$data
        ]);
    }

#[Route('/backrec/delete{idrec}', name: 'backdelete')]
public function backdelete($idrec){
    $data = $this->getDoctrine()->getRepository(Reclamation::class)->find($idrec);
    $em = $this->getDoctrine()->getManager();
    $em->remove($data);
    $em->flush();

    return $this->redirectToRoute('app_backrec');
}
#[Route('/showclaims', name: 'Claims_show')]
public function show(ClaimsRepository $rep, Request $request): Response
{
    // Retrieve search term and sort parameters from the request
    $searchTerm = $request->query->get('search', '');

    // Fetch claims based on search criteria and sort parameters
    $claims = $rep->findBySearchCriteriaAndSort($searchTerm);

    // Check if the request is an AJAX request
    if ($request->isXmlHttpRequest()) {
        // If AJAX request, render the part of the template for the table body
        return $this->render('Claims/search.html.twig', [
            'Claimss' => $claims,
            'searchTerm' => $searchTerm,
            
        ]);
    }

    // Render the full page for non-AJAX requests
    return $this->render('Claims/index.html.twig', [
        'Claimss' => $claims,
        'searchTerm' => $searchTerm,
        
    ]);
}


}