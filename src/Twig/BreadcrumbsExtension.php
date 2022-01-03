<?php


namespace App\Twig;

use App\Entity\CityPages;
use App\Entity\Content;
use App\Entity\Contracts\PageInterface;
use App\Entity\Products;
use App\Entity\StoneCatalog;
use App\Entity\StoneProduct;
use App\Service\BreadcrumbsService;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Component\HttpFoundation\Response;


class BreadcrumbsExtension extends AbstractExtension
{
    /**
     * @var BreadcrumbsService
     */
    protected $breadcrumbs_service;

    public function __construct(BreadcrumbsService $breadcrumbs_service)
    {

        $this->breadcrumbs_service = $breadcrumbs_service;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('breadcrumbs', [$this, 'breadcrumbs'], ['needs_environment'=> true, 'is_safe' => ['html']]),
            new TwigFunction('product_breadcrumbs', [$this, 'product_breadcrumbs'], ['needs_environment'=> true, 'is_safe' => ['html']]),
            new TwigFunction('stoneCatalog_braedcrumbs', [$this, 'stoneCatalog_braedcrumbs'], ['needs_environment'=> true, 'is_safe' => ['html']]),
            new TwigFunction('stoneProduct_breadcrumbs', [$this, 'stoneProduct_breadcrumbs'], ['needs_environment'=> true, 'is_safe' => ['html']]),
            new TwigFunction('city_breadcrumbs', [$this, 'city_breadcrumbs'], ['needs_environment'=> true, 'is_safe'=>['html']]),
        ];
    }


    public function breadcrumbs(Environment $twig, Content $page, string $current_name = null):string
    {
        if (is_null($page)) {
            return '';
        }
        $items = $this->breadcrumbs_service->getItems($page, $current_name);
        if (count($items) < 1) {
            return '';
        }
        return $twig->render('extensions/breadcrumbs.html.twig', compact( 'items','page'));

    }

    public function product_breadcrumbs(Environment $twig, Products $page, string $current_name = null):string
    {
        if (is_null($page)) {
            return '';
        }
        $items = $this->breadcrumbs_service->getProductItems($page, $current_name);
        if (count($items) < 1) {
            return '';
        }
        return $twig->render('extensions/breadcrumbs.html.twig', compact( 'items','page'));

    }

    public function stoneCatalog_braedcrumbs(Environment $twig, StoneCatalog $page, string $current_name = null):string
    {
        if (is_null($page)) {
            return '';
        }
        $items = $this->breadcrumbs_service->getStoneCatalogItems($page, $current_name);
        if (count($items) < 1) {
            return '';
        }
        return $twig->render('extensions/breadcrumbs.html.twig', compact( 'items','page'));
    }

    public function stoneProduct_breadcrumbs(Environment $twig, StoneProduct $page, string $current_name = null):string{
        if (is_null($page)) {
            return '';
        }
        $items = $this->breadcrumbs_service->getStoneProductItems($page, $current_name);
        if (count($items) < 1) {
            return '';
        }
        return $twig->render('extensions/breadcrumbs-stone.html.twig', compact( 'items','page'));
    }

    public function city_breadcrumbs(Environment $twig, CityPages $page, string $current_name = null):string{
        if (is_null($page)) {
            return '';
        }
        $items = $this->breadcrumbs_service->getCityPageItems($page, $current_name);
        if (count($items) < 1) {
            return '';
        }
        return $twig->render('extensions/breadcrumbs_city.html.twig', compact( 'items','page'));

    }


}