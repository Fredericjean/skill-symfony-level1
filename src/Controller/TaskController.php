<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/task', name: 'app.task.')]
class TaskController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly TaskRepository $taskRepository,
    ){

    }

    #[Route( name: 'index', methods :['GET'])]
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $this->taskRepository->findAll(),
        ]);
    }

    #[Route('create', name:'create', methods: ['GET', 'POST'])]
    public function create (Request $request): Response | RedirectResponse
    {
        $task = new Task;
        $form = $this->createForm(TaskType::class, $task);
        $form ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($task);
            $this->em->flush();
            $this->addFlash('success', 'Tâche créée avec succès');

            return $this->redirectToRoute('app.task.index');
        }

        return $this->render('task/create.html.twig',[
            'form'=> $form
        ]);
    }

    #[Route('{id}/edit', name:'edit', methods:['GET', 'POST'])]
    public function edit(Request $request, Task $task): Response | RedirectResponse
    {
        if (!$task){
            $this->addFlash('danger', "La tâche n'a pas été trouvée");
            
            return $this->redirectToRoute('app.task.index');
        }

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->em->persist($task);
            $this->em->flush();
            $this->addFlash('success', 'Tâche mis à jour');

            return $this->redirectToRoute('app.task.index');
        }

        return $this->render ("task/edit.html.twig", [
            'form' => $form
        ]);
    }

    #[Route('{id}/delete', name:'delete', methods: ['POST'])]
    public function delete (Task $task, Request $request): RedirectResponse
    {
        if (!$task){
            $this->addFlash('error','Cette tâche n\'a pas été trouvée');

            return $this->redirectToRoute('app.task.index');
        }

        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
            $this->em->remove($task);
            $this->em->flush();
            $this->addFlash('success', 'Cette tâche a été supprimée');
        } else {
            $this->addFlash('error', 'Token CSRF invalid');
            return $this->redirectToRoute('app.task.index');
        }

        return $this->redirectToRoute('app.task.index');
    }
}
