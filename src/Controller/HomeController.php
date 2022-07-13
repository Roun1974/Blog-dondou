<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Service\ArticleService;
use App\Form\Type\WelcomeType;
use App\Model\WelcomeModel;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Option;
use App\Entity\User;
use App\Service\OptionService;

class HomeController extends AbstractController
{
    public function __construct(private OptionService $optionService)
    {
    }
    #[Route('/', name: 'app_home')]
    public function index(ArticleService $articleService,CategoryRepository $categoryRepo): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
                'articles' => $articleService->getPaginatedArticles(),
                'categories' => $categoryRepo->findAll()
            ]);
        
    }
    #[Route('/welcome', name: 'welcome')]
    public function welcome(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, OptionService $optionService): Response
    {
        if ($this->optionService->getValue(WelcomeModel::SITE_INSTALLED_NAME)) {
            return $this->redirectToRoute('app_home');
        }

        $welcomeForm = $this->createForm(WelcomeType::class, new WelcomeModel());

        $welcomeForm->handleRequest($request);

        if ($welcomeForm->isSubmitted() && $welcomeForm->isValid()) {
            /** @var WelcomeModel $data */
            $data = $welcomeForm->getData();

            $siteTitle = new Option(WelcomeModel::SITE_TITLE_LABEL, WelcomeModel::SITE_TITLE_NAME, $data->getSiteTitle(), TextType::class);
            $siteInstalled = new Option(WelcomeModel::SITE_INSTALLED_LABEL, WelcomeModel::SITE_INSTALLED_NAME, true, null);

            $user = new User($data->getUsername());
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($passwordHasher->hashPassword($user, $data->getPassword()));

            $em->persist($siteTitle);
            $em->persist($siteInstalled);

            $em->persist($user);

            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/welcome.html.twig', [
            'form' => $welcomeForm->createView()
        ]);
    }
}
