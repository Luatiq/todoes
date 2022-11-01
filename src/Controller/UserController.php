<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'user')]
#[IsGranted('ROLE_ADMIN')]
class UserController extends AbstractController
{
    public function __construct(
        private UserRepository         $repository,
        private EntityManagerInterface $em
    )
    {
    }

    #[Route('/overview', name: '.overview')]
    public function overview(
        Request $request
    )
    {
        return $this->render('user/overview-data.html.twig', [
            // Todo use qb and filter to only retrieve ROLE_USER by default
            'items' => $this->repository->findAll(),
        ]);
    }

    #[Route('/{user}/edit', name: '.edit')]
    public function edit(
        Request        $request,
        User           $user
    ): Response
    {
        $form = $this->createForm(UserType::class, $user, ['isPasswordEditable' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('user.overview');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{user}/delete', name: '.delete')]
    public function delete(
        Request $request,
        User $user
    ): Response {
        if ($this->getUser() === $user) return $this->redirectToRoute('profile.edit');

        $this->em->remove($user);
        $this->em->flush();

        return $this->redirectToRoute('user.overview');
    }
}