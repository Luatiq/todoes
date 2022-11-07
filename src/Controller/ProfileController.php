<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile', name: 'profile')]
#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {
    }

    #[Route('/edit', name: '.edit')]
    public function edit(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user, [
            'isPasswordEditable' => !$this->isGranted('ROLE_PREVIOUS_ADMIN')
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userEntity = $userRepository->findOneBy(['email'=>$user->getUserIdentifier()]);

            if (!$this->isGranted('ROLE_PREVIOUS_ADMIN') && strlen($form->get('plainPassword')->getData()) >= 6) {
                $userRepository->upgradePassword($userEntity, $passwordHasher->hashPassword($userEntity, $form->get('plainPassword')->getData()));

                $this->addFlash('success', 'message.pass_changed_successfully');
            }

            $this->em->persist($userEntity);
            $this->em->flush();
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}