<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Service\ArticleService;

class CategoryController extends AbstractController
{
    #[Route('/category/{slug}', name: 'category_show')]
    public function show(?Category $categories, ArticleService $articleService ): Response
    {
        if (!$categories) {
        return $this->redirectToRoute('app_home');
    }
        return $this->render('category/show.html.twig', [
            
            'Category' => $categories,
            'articles' => $articleService->getPaginatedArticles($categories),
        ]);
    }
}
