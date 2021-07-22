<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContentRepository;

class HomeController extends AbstractController
{
    /**
     * @var ContentRepository
     */
    protected $page_repository;

    public function __construct(ContentRepository $repository){
        $this->page_repository = $repository;
    }
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        if(!$page = $this->page_repository->findOneBy(['path'=>'/']) ){
            throw $this->createNotFoundException('Main page not found');
        }

        return $this->render('home/index.html.twig', [
            'page' => $page,
        ]);
    }
}
