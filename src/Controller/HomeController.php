<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Question;
use App\Form\QuestionType;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine, Request $request, PaginatorInterface $paginator): Response
    {

        $categories = $doctrine
            ->getRepository(Category::class)
            ->findAll();


        $question_for_form = new Question();

        $form = $this->createForm(QuestionType::class, $question_for_form, [
            'action' => $this->generateUrl('app_home')
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            $question_for_form->setStatus(False);
            $question_for_form->setDate(new \DateTime('now'));
            $question_for_form->setUser($this->getUser());
            $answer = $form->getData();
            //var_dump($answer); die;
            $em = $doctrine->getManager();

            # получаю категорию
            $category = $question_for_form->getCategory();
            $category->setStatus(True);

            # проверка, что такой категории еще нет
            $categoryCheck = $doctrine
                ->getRepository(Category::class)
                ->findOneBy(['name' => $category->getName()]);


            # если такой нет, то добавляю, иначе устанавливаю уже имеющеюся
            if (!$categoryCheck) {
                $em->persist($category);
            } else {
                $question_for_form->setCategory($categoryCheck);
            }
            $em->persist($question_for_form);

            # отправляю полученные данные в БД
            $em->flush();


            $em->persist($answer);
            $em->flush();
            // ... perform some action, such as saving the task to the database




            //return $this->redirectToRoute('app_answer', ['id'=>$id]);
        }

        $question = $doctrine->getRepository(Question::class)->findBy(['status' => 'true'],['date' => 'DESC']);
        $topQuestion = $doctrine->getRepository(Question::class)->getTopQuestion();

        $appointments = $paginator->paginate(
        // Doctrine Query, not results
            $question,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        return $this->render('home/index.html.twig', [
            'questions' => $appointments,
            'topQuestion' => $topQuestion,
            'formQuestion' => $form->createView(),
            'categories' => $categories,
            'appointments' => $appointments,
        ]);
    }
}
