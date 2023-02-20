<?php

namespace App\Controller;

use App\Form\BlogPostType;
use App\Service\GenericRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/post')]
class BlogPostController extends AbstractController
{
    #[Route('/', name: 'post_index', methods: ['GET'])]
    public function index(GenericRequest $genericRequest): Response
    {
        $response = $genericRequest->getRequest('GET', 'blogposts_show');

        return $this->render('BlogPost/index.html.twig', [
            'blogpost' => json_decode($response->getContent(), true),
        ]);
    }

    #[Route('/new', name: 'post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GenericRequest $genericRequest): Response
    {
        $categories = $this->getAllCategories($request, $genericRequest);
        $form = $this->createForm(BlogPostType::class, null, [
            'categories' => $categories,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $request->request->all()['blog_post'];
            if (! $this->isCsrfTokenValid('post_token', $post['_token'])) {
                return $this->renderForm('BlogPost/new.html.twig', [
                    'form' => $form,
                ]);
            }
            unset($post['_token']);
            $post = json_encode($post);
            $response = $genericRequest->getRequest('POST', 'blogpost', $post, $request->getSession()->get('bearer'));

            return $this->redirectToRoute('post_index', [
                'new_message' => json_decode($response->getContent()),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('BlogPost/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'post_show', methods: ['GET'])]
    public function show($id, GenericRequest $genericRequest): Response
    {
        $response = $genericRequest->getRequest('GET', 'blogpost_show/'.$id);

        return $this->render('BlogPost/show.html.twig', [
            'post' => json_decode($response->getContent(), true),
        ]);
    }

    protected function getAllCategories(Request $request, GenericRequest $genericRequest): array
    {
        $response = $genericRequest->getRequest('GET', 'categories/', null, $request->getSession()->get('bearer'));

        $categories = [];
        foreach (json_decode($response->getContent(), true) as $category) {
            $categories[$category['name']] = $category['id'];
        }

        return $categories;
    }
}
