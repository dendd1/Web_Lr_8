<?php

namespace App\Controller\Admin;

use App\Entity\Aswer;
use App\Entity\Category;
use App\Entity\Question;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
         $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
         return $this->redirect($adminUrlGenerator->setController(AswerCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
        ->setTitle('Панель Админа')
        // you can include HTML contents too (e.g. to link to an image)

        // the path defined in this method is passed to the Twig asset() function
        //->setFaviconPath('favicon.svg')

        // the domain used by default is 'messages'
        //->setTranslationDomain('my-custom-domain')

        // there's no need to define the "text direction" explicitly because
        // its default value is inferred dynamically from the user locale
        //->setTextDirection('ltr')

        // set this option if you prefer the page content to span the entire
        // browser width, instead of the default design which sets a max width
        ->renderContentMaximized()

        // set this option if you prefer the sidebar (which contains the main menu)
        // to be displayed as a narrow column instead of the default expanded design
        //->renderSidebarMinimized()

        // by default, users can select between a "light" and "dark" mode for the
        // backend interface. Call this method if you prefer to disable the "dark"
        // mode for any reason (e.g. if your interface customizations are not ready for it)
        //->disableDarkMode()

        // by default, all backend URLs are generated as absolute URLs. If you
        // need to generate relative URLs instead, call this method
        ->generateRelativeUrls();
    }

    public function configureMenuItems(): iterable
    {
        //yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToUrl('На главную', 'fas fa-home', '/');
        yield MenuItem::linkToCrud('Вопросы', 'fas fa-question-circle', Question::class);
        yield MenuItem::linkToCrud('Категории', 'fas fa-info', Category::class);
        yield MenuItem::linkToCrud('Ответы', 'fas fa-comments', Aswer::class);
        yield MenuItem::linkToLogout('Выход', 'fas fa-sign-out');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
