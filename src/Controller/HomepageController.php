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

    
}
