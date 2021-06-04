<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContentRepository;

class PageController extends AbstractController
{
    /**
     * @var ContentRepository
     */
   protected $page_repository;

   public function __construct(ContentRepository $repository)
   {
       $this->page_repository = $repository;
   }

    /**
     * @Route("/{token}", name="dynamic_pages",requirements={"token"= ".+\/$"})
     */

    public function index($token){
        if(!$page = $this->page_repository->findOneBy(['path'=>$token])){
            throw $this->createNotFoundException(sprintf('Page %s not found', $token));
        }
        if($page->getPageType() == 'category'){
            return $this->category($page);
        }
    }

    private function category($category){
        return $this->render('page/category.html.twig',[
           'category'=>$category
        ]);
    }
}
