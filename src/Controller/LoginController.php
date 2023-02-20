<?php

namespace App\Controller;

use App\Service\GenericRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('Login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

   #[Route('/login_api', name: 'login_api')]
   public function check(Request $request, GenericRequest $genericRequest): Response
   {
       $session = $request->getSession();
       $body = json_encode([
           'username' => $request->request->get('_username'),
           'password' => $request->request->get('_password'),
       ]);

       $response = $genericRequest->getRequest('POST', 'login_check_api', $body);

       if (200 !== $response->getStatusCode()) {
           return $this->render('Login/index.html.twig', [
               'last_username' => $request->request->get('_username'),
               'error'         => 'Wrong username or password',
           ]);
       }
       $token = json_decode($response->getContent(), true);
       $session->set('bearer', $token['token']);
       $session->set('username', $request->request->get('_username'));

       return $this->redirectToRoute('post_index', [], Response::HTTP_SEE_OTHER);
   }

   #[Route('/logout', name: 'logout')]
   public function logout(Request $request): Response
   {
       $session = $request->getSession();

       $session->invalidate();

       return $this->redirectToRoute('post_index', [], Response::HTTP_SEE_OTHER);
   }
}
