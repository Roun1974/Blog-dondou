<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class CommentController extends AbstractController
{
    #[Route('/ajax/comments', name: 'comment_add', methods: ['POST'])]
    public function add(Request $request,CommentRepository $commentRepo,ArticleRepository $articleRepo,UserRepository $userRepo,EntityManagerInterface $entityManager): Response
    {
        $commentData = $request->request->all ('comment');
        if (!$this->isCsrfTokenValid('comment-add', $commentData['_token'])) {
            return $this->json([
                'code' => 'INVALID_CSRF_TOKEN'
            ], Response::HTTP_BAD_REQUEST);
        }
        $article = $this->articleRepo->findOneBy(['id' => $commentData['article']]);
        if (!$article) {
            return $this->json([
                'code' => 'ARTICLE_NOT_FOUND'
            ], Response::HTTP_BAD_REQUEST);
        }
        $user = $this->getUser();
        
        if($user){
          return $this->json([
            'code' => 'USER_NOT_AUTHENTICATED_FULLY'
        ], Response::HTTP_BAD_REQUEST);
        }

        $comment= new Comment($article);
        $comment->setContent($commentData['content']);
        $comment->setUser($user);
        $comment->setCreatedAt(new \Datetime());
        $comment->handleRequest($request);
        
        $entityManager->persist($comment);
        $entityManager->flush();

        $html = $this->renderView('comment/index.html.twig', [
            'comment' => $comment
        ]);

        return $this->json([
            'code' => 'COMMENT_ADDED_SUCCESSFULLY',
            'message' => $html,
            'numberOfComments' => $this->$commentRepo->count(['article' => $article])
        ]);
    }
}
