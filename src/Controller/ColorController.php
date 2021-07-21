<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ColorRepository;
use App\Repository\ProductsRepository;
use Knp\Component\Pager\Paginator;

class ColorController extends AbstractController
{
    /**
     * @var ColorRepository
     */
    protected $color_repository;
    protected $products_repository;
    /**
     * @var PaginatorInterface
     */
    protected $paginator;

    public function __construct(ColorRepository $color_repository, ProductsRepository $products_repository, PaginatorInterface $paginator)
    {
        $this->color_repository = $color_repository;
        $this->products_repository = $products_repository;
        $this->paginator = $paginator;
    }


    /**
     * @Route("/color/{category}/{token}/{sort}", name="color")
     */
    public function index($category, $token, $sort, PaginatorInterface $paginator, Request $request): Response
    {
        if(!$color = $this->color_repository->find($token)){
            throw $this->createNotFoundException('Color not found');
        }
        if($sort == 'no') {
            $products = $this->products_repository->findBy(['category_id' => $category, 'color' => $color]);

        }else{
            $products = $this->products_repository->findBy(['category_id' => $category, 'color' => $color], ['price' => $sort]);
        }
        $pagination = $paginator->paginate(
            $products, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            2 /*limit per page*/
        );
        $pagination->setParam('_fragment', 'catalog');
            $html = '';
            if ($products) {
                return $this->render('color/index.html.twig', [
                    'products' => $products,
                    'pagination' => $pagination
                ]);
            }

        return new Response($html);
    }

    /**
     * @Route("/sort/{category}/{token}/{sort}", name="sort")
     */
    public function sort($category, $token, $sort):Response{
        if($token == 0){
            $products = $this->products_repository->findBy(['category_id' => $category],['price' => $sort]);
        }else{
            if(!$color = $this->color_repository->find($token)){
                throw $this->createNotFoundException('Color not found');
            }
            $products = $this->products_repository->findBy(['category_id' => $category, 'color' => $color], ['price'=> $sort]);
        }
        $html = '';
        if ($products) {
            return $this->render('color/index.html.twig', [
                'products' => $products,
            ]);
        }

        return new Response($html);
    }

    /**
     * @Route("/clear-filter/{category}/{sort}", name="clearFilter")
     */
    public function clear($category, $sort):Response{
        $html = '';
        if($category){
            if($sort == 'no') {
                $products = $this->products_repository->findBy(['category_id' => $category]);
            }else{
                $products = $this->products_repository->findBy(['category_id' => $category], ['price' => $sort]);
            }
            if ($products) {
                return $this->render('color/index.html.twig', [
                    'products' => $products,
                ]);
            }
        }
        return new Response($html);
    }
}
