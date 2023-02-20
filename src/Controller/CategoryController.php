<?php

namespace App\Controller;

use App\Form\CategoryType;
use App\Service\GenericRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'categories_index', methods: ['GET'])]
    public function index(Request $request, GenericRequest $genericRequest): Response
    {
        $response = $genericRequest->getRequest('GET', 'categories', null, $request->getSession()->get('bearer'));

        return $this->render('Category/index.html.twig', [
            'categories' => json_decode($response->getContent(), true),
        ]);
    }

    #[Route('/new', name: 'category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GenericRequest $genericRequest): Response
    {
        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $request->request->all()['category'];
            if (! $this->isCsrfTokenValid('category_token', $category['_token'])) {
                return $this->renderForm('Category/new.html.twig', [
                    'form' => $form,
                ]);
            }

            unset($category['_token']);
            $category = json_encode($category);
            $response = $genericRequest->getRequest('POST', 'category', $category, $request->getSession()->get('bearer'));

            return $this->redirectToRoute('categories_index', [
                'new_message' => json_decode($response->getContent()),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Category/new.html.twig', [
            'form' => $form,
        ]);
    }
}
