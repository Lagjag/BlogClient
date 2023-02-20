<?php

namespace App\Controller;

use App\Service\GenericRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(): Response
    {
        return $this->render('Register/index.html.twig', [
            'last_username' => '',
            'last_email'    => '',
        ]);
    }

    #[Route('/register_api', name: 'register_api')]
    public function check(Request $request, GenericRequest $genericRequest): Response
    {
        $httpClient = HttpClient::create();
        $body = json_encode([
            'email' => $request->request->get('_email'),
            'username' => $request->request->get('_username'),
            'password' => $request->request->get('_password'),
        ]);

        $response = $genericRequest->getRequest('POST', 'register', $body);

        if (str_contains($response->getContent(), 'try with another')) {
            return $this->render('Register/index.html.twig', [
                'last_username' => $request->request->get('_username'),
                'last_email'    => $request->request->get('_email'),
                'error'         => $response->getContent(),
            ]);
        }

        return $this->redirectToRoute('app_login', [
            'new_message' => 'Successfully registered user',
        ], Response::HTTP_SEE_OTHER);
    }
}
