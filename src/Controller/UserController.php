<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/user', name: 'user')]
class UserController extends AbstractController
{
    public function __construct(
        private UserRepository         $repository,
        private EntityManagerInterface $em
    )
    {
    }

    #[IsGranted('ROLE_ADMIN')]
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

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{user}/edit', name: '.edit')]
    public function edit(
        Request $request,
        User    $user
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

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{user}/delete', name: '.delete')]
    public function delete(
        User $user
    ): Response
    {
        if ($this->getUser() === $user) return $this->redirectToRoute('profile.edit');

        $this->em->remove($user);
        $this->em->flush();

        return $this->redirectToRoute('user.overview');
    }

    #[Security("is_granted('ROLE_ALLOWED_TO_SWITCH') or is_granted('ROLE_PREVIOUS_ADMIN')")]
    #[Route('/impersonate/{user}', name: '.impersonate')]
    public function impersonate(
        TranslatorInterface $translator,
        ?User               $user = null,
    ): Response
    {
        if (!$user) {
            $this->addFlash('success', $translator->trans('message.exited_impersonation'));
            return $this->redirectToRoute('profile.edit', ['_switch_user' => '_exit']);
        }

        $this->addFlash('success', $translator->trans('message.impersonation_successful'));

        return $this->redirectToRoute('profile.edit', [
            '_switch_user' => $user->getEmail(),
        ]);
    }
}