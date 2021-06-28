<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ColorRepository;
use App\Repository\ProductsRepository;

class ColorController extends AbstractController
{
    /**
     * @var ColorRepository
     */
    protected $color_repository;
    protected $products_repository;

    public function __construct(ColorRepository $color_repository, ProductsRepository $products_repository)
    {
        $this->color_repository = $color_repository;
        $this->products_repository = $products_repository;
    }


    /**
     * @Route("/color/{category}/{token}/{sort}", name="color")
     */
    public function index($category, $token, $sort): Response
    {
        if(!$color = $this->color_repository->find($token)){
            throw $this->createNotFoundException('Color not found');
        }
        if($sort == 'no') {
            $products = $this->products_repository->findBy(['category_id' => $category, 'color' => $color]);
        }else{
            $products = $this->products_repository->findBy(['category_id' => $category, 'color' => $color], ['price' => $sort]);
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
