<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Service\ArticleService;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ArticleService $articleService,CategoryRepository $categoryRepo): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
                'articles' => $articleService->getPaginatedArticles(),
                'categories' => $categoryRepo->findAll()
            ]);
        
    }
}
