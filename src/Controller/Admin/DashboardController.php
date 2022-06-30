<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use App\Entity\Article;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    ) {}
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url= $this->adminUrlGenerator->setController(ArticleCrudController::class)->generateUrl();
        return $this->redirect($url);

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Blog Dondou');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::SubMenu('Articles', 'fas fa-newspaper')->setSubItems([
              MenuItem::linkToCrud('Tous les articles', 'fas fa-newspaper', Article::class)
        ]);
    }
}
