<?php

namespace App\Controller\Admin;

use App\Entity\Article;                                      
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;  
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        // Wenn Du zur CRUD-Liste weiterleiten willst, statt parent::index():
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect(
            $adminUrlGenerator
                ->setController(ArticleCrudController::class)  // ← auch importieren, wenn nötig
                ->generateUrl()
        );

        // Andernfalls simply return parent::index():
        // return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Admin Panel Skeleton');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Articles', 'fa fa-file-text', Article::class);
    }
}
