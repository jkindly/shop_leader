<?php


namespace App\Controller;


use App\Entity\Task;
use App\Form\TaskFormType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class TaskController extends BaseController
{
    /**
     * @Route("/task", name="app_task_index")
     */
    public function index(TaskRepository $taskRepository): Response
    {
        $tasks = $taskRepository->getUserRelatedTasks($this->getUser());

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/task/new", name="app_task_new")
     */
    public function new(EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(TaskFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var Task $task */
            $task = $form->getData();
            $task->setAuthor($this->getUser());
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'Zadanie zostało dodane');

            return $this->redirectToRoute('app_task_index');
        }

        return $this->render('task/new.html.twig', [
            'taskForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/task/{id<\d+>}/edit", name="app_task_edit")
     */
    public function edit(Task $task, Request $request, EntityManagerInterface $em): Response
    {
        if ($task->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN'))
        {
            throw new AccessDeniedException('Access Denied. You are not owner of this task.');
        }

        $form = $this->createForm(TaskFormType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var Task $task */
            $task = $form->getData();
            $task->setAuthor($this->getUser());
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'Zadanie zostało edytowane');

            return $this->redirectToRoute('app_task_view', [
                'id' => $task->getId()
            ]);
        }

        return $this->render('task/edit.html.twig', [
            'taskForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/task/{id<\d+>}", name="app_task_view")
     */
    public function view(Task $task): Response
    {
        $has_access = false;

        if ($task->getAuthor() === $this->getUser())
        {
            $has_access = true;
        }
        else
        {
            foreach ($task->getUsers() as $user)
            {
                if ($user === $this->getUser())
                {
                    $has_access = true;
                    break;
                }
            }
        }

        if ($has_access || $this->isGranted('ROLE_ADMIN'))
        {
            return $this->render('task/view.html.twig', [
                'task' => $task,
            ]);
        }

        throw new AccessDeniedException('Access Denied. You are not added to this task.');

    }
}