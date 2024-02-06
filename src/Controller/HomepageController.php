<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\Type\ContactType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

final class HomepageController extends AbstractController
{
    #[Route('/{page}/{limit}', name: 'homepage', requirements: ['page' => '\d+', 'limit' => '\d+'])]
    public function indexAction(
        EntityManagerInterface $entityManager, 
        Request $request,
        int $page = 1,
        int $limit = 3
    ): Response
    {
        
        //load data per page to variable
        $contacts = $entityManager->getRepository(Contact::class)->findByPage($page, $limit);
        //load homepage 
        return $this->render('default/homepage.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    
    #[Route('/add', name: 'add')]
    public function addAction(
        EntityManagerInterface $entityManager, 
        Request $request,
    ): Response
    {
            $contact = new Contact();
            $form = $this->createForm(ContactType::class, $contact);
            $form->handleRequest($request);
            //check if data is ubmitted and valid
            if ($form->isSubmitted() && $form->isValid()) {
                
                //Now we get the code and save it
                $contact = $form->getData();
                $slug = preg_replace('/[^\p{L}\p{N}-]+/u', '', $contact->getLastname());
                $slug = preg_replace('/\b\w*\d\w*\b-?/', '', $slug);
                $contact->setSlug($slug);
                $contact->setDeleted(0);
                $entityManager->persist($contact);
                $entityManager->flush();


                //redirect to homepage
                return $this->redirect('/');
            }
            
            return $this->render('default/form.html.twig', [
                'form' => $form
            ]);
        
    }


    #[Route('/{slug}', name: 'edit')]
    public function editAction(
        EntityManagerInterface $entityManager, 
        Request $request,
        string $slug,
    ): Response
    {   
        $new = htmlspecialchars($slug, ENT_QUOTES);
        //we divide slug into name and id
        $array = explode("-", $slug);

        //check if id is numeric
        if(is_numeric($array[1])){

            //find contact by id
            $contact = $entityManager->getRepository(Contact::class)->findOneBy([
                'id' => $array[1]
            ]);

            //creat form from ContactType
            $form = $this->createForm(ContactType::class, $contact);
            
            $form->handleRequest($request);
            //check if data is ubmitted and valid
            if ($form->isSubmitted() && $form->isValid()) {
                
                //Now we get the code and save it
                $contact = $form->getData();
                $slug = preg_replace('/[^\p{L}\p{N}-]+/u', '', $contact->getLastname());
                $slug = preg_replace('/\b\w*\d\w*\b-?/', '', $slug);
                $contact->setSlug($slug);

                $entityManager->persist($contact);
                $entityManager->flush();


                //redirect to homepage
                return $this->redirect('/');
            }
            
            return $this->render('default/form.html.twig', [
                'form' => $form
            ]);
        }else{
            //redirect to error page if someone plays with URL - joke
            return $this->render('default/error.html.twig', [
                'message' => 'Ne, nehraj si s URL adresou'
            ]);
        }
    }

    
    #[Route('/remove/{id}', name: 'remove')]
    public function removeAction(
        EntityManagerInterface $entityManager, 
        Request $request,
        int $id,
    ): Response
    {

        $contact = $entityManager->getRepository(Contact::class)->findOneBy([
            'id' => $id
        ]);
        $contact->setDeleted(1);
        $contact->setDeletedFrom(new DateTime());
        
        $entityManager->persist($contact);
        $entityManager->flush();

        return $this->redirect('/');
    }

    
}
