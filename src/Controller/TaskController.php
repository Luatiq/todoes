<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_USER')]
#[Route('/task', name: 'task')]
class TaskController extends AbstractController
{
    public function __construct(
        private TaskRepository         $repository,
        private EntityManagerInterface $em,
    )
    {
    }

    #[Route('/overview', name: '.overview')]
    public function overview()
    {
        return $this->render('task/overview-data.html.twig', [
            // Todo filters
            'items' => $this->repository->getTasks($this->getUser()),
        ]);
    }

    #[Route('/{entity}/edit', name: '.edit', defaults: ['entity'=>'new'])]
    public function edit(
        Request $request,
        ?Task    $entity,
    ): Response
    {
        // Todo use securityVoter for this
        if (!$this->isGranted('ROLE_ADMIN') && $this->getUser() !== $entity->getCreatedBy()) {
            return $this->redirectToRoute('task.overview');
        }

        if (!$entity) $entity = new Task();

        $form = $this->createForm(TaskType::class, $entity, [
            'isOwnTask' => $entity->getId() && $entity->getCreatedBy() === $this->getUser(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($entity);
            $this->em->flush();

            return $this->redirectToRoute('task.overview');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{entity}/delete', name: '.delete')]
    public function delete(
        Task $entity
    ): Response
    {
        $this->em->remove($entity);
        $this->em->flush();

        return $this->redirectToRoute('task.overview');
    }
}