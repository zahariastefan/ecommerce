<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Totp\TotpAuthenticatorInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\QrCode\QrCodeGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class SecurityController extends AbstractController
{
    #[Route('/security', name: 'app_security')]
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('security/login.html.twig',[
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'last_username' => $authenticationUtils->getLastUsername()
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(AuthenticationUtils $authenticationUtils): Response
    {
        throw new \Exception('logout() should never be reached');
    }

    /**
     * @Route("/authenticate/2fa/enable", name="app_2fa_enable")
     * @IsGranted("ROLE_USER")
     */
    public function enable2fa(TotpAuthenticatorInterface $totpAuthenticator, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        if (!$user->isTotpAuthenticationEnabled()) {
            $user->setTotpSecret($totpAuthenticator->generateSecret());

            $entityManager->flush();
        }

//        dd($user->getTotpSecret());
        return $this->render('security/enable2fa.html.twig');
    }

    /**
     * @Route("/authenticate/2fa/disable", name="app_2fa_disable")
     * @IsGranted("ROLE_USER")
     */
    public function disable2fa(TotpAuthenticatorInterface $totpAuthenticator, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        if ($user->isTotpAuthenticationEnabled()) {
            $user->setTotpSecret(null);

            $entityManager->flush();
            $alertDisabled2fa = 1;
        }

        if(!isset($alertDisabled2fa)) $alertDisabled2fa = 1;
        return $this->redirectToRoute('app_homepage',[
            'alertDisabled2fa' => $alertDisabled2fa
        ]);
    }

    /**
     * @Route("/authentication/2fa/qr-code", name="app_qr_code")
     * @IsGranted("ROLE_USER")
     */
    public function displayGoogleAuthenticatorQrCode(QrCodeGenerator $qrCodeGenerator)
    {
        // $qrCode is provided by the endroid/qr-code library. See the docs how to customize the look of the QR code:
        // https://github.com/endroid/qr-code
        $qrCode = $qrCodeGenerator->getTotpQrCode($this->getUser());
//        $qrCodeContent = $container->get("scheb_two_factor.security.totp_authenticator")->getQRContent($user);

//        dd($qrCode);
        return new Response($qrCode->writeString(), 200, ['Content-Type' => 'image/png']);
    }
}
