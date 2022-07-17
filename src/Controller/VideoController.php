<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\VideoRepository;
use App\Form\Type\VideoType;
use App\Service\VideoService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class VideoController extends AbstractController
{
    /**
     * @Route("/ajoutvideo", name="app_ajoutVideo")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function ajoutVideo(RequestStack $requestStack,VideoService $videoService,VideoRepository $videoRepo): Response
    {
        $request = $requestStack->getMainRequest();
        $videoForm = $this->createForm(VideoType::class, $videoRepo->new());

        $videoForm->handleRequest($request);
        if ($videoForm->isSubmitted() && $videoForm->isValid()) {
            return $videoService->handleVideoForm($videoForm);
        }
            return $this->render('video/ajout_video.html.twig',[
             'form'=>$videoForm->createView(),
             'videos' => $videoRepo->findAll()
        ]);
    }
    #[Route('/video', name: 'app_video')]
    public function index(RequestStack $requestStack,VideoService $videoService,VideoRepository $videoRepo): Response
    {
        return $this->render('video/video_file.html.twig', [
            'controller_name' => 'VideoController',
            'videos' => $videoRepo->findAll()
        ]);
    }
}
