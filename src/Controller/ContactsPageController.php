<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactsPageController extends AbstractController
{
    #[Route('/contacts/page', name: 'app_contacts_page')]
    public function index(): Response
    {
        return $this->render('contacts_page/index.html.twig', [
            'controller_name' => 'ContactsPageController',
        ]);
    }
}
