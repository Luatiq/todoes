<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'base')]
class BaseController extends AbstractController
{
    #[Route('/', name: '.home')]
    function index(): Response
    {
        return $this->render('home.html.twig');
    }
}