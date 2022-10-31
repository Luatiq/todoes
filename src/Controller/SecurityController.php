<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/', name: 'auth')]
class SecurityController extends AbstractController
{
    #[Route('/register', name: '.register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        // Redirect to account-page when we have that
        if ($this->getUser()) return $this->redirectToRoute('base.home');

        $newUser = new User();
        $form = $this->createForm(UserType::class, $newUser, ['isRegistrationForm'=>true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $newUser->setPassword(
                $userPasswordHasher->hashPassword(
                    $newUser,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($newUser);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('base.home');
        }

        return $this->render('authentication/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/login', name: '.login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: '.logout')]
    public function logout()
    {
        return $this->redirectToRoute('auth.login');
//        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
