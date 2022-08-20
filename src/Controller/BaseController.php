<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class BaseController extends AbstractController
{
    #[Route('/base', name: 'app_base')]
    public function index(): Response
    {

        if(!empty($this->getUser())){
            $timeLoggedIn = $_SESSION['_sf2_meta']['c'];

            if((time()) >= ($timeLoggedIn+86400)){
                $timePassed = true;
            }else{
                $timePassed = false;
            }
        }else{
            $timePassed = false;
        }

        return new JsonResponse([
            'timePassed'=>$timePassed
        ]);
    }
}
