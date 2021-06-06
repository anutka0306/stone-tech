<?php


namespace App\Widget;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\RatingRepository;
use App\Repository\ContentRepository;


class TopMenu extends AbstractController
{
    public function show(ContentRepository $contentRepository){
        $menu = array();
        $parentItems = $contentRepository->findBy(
            [
                'parent' => 1,
                'top_menu' => 1
            ],
            ['menu_order' => 'ASC']
        );

        foreach ($parentItems as $key => $item){
            $menu[$key] = array(
                $key => array($item->getMenuName(), $item->getPath()),
                'children' => array()
            );
            $children_array = $contentRepository->findBy(
                [
                    'parent' => $item->getId(),
                    'top_menu' => 1
                ],
                ['menu_order' => 'ASC']
            );
            foreach ($children_array as $count =>$child){
               $menu[$key]['children'][$count] = array(
                   $child->getMenuName(),
                   $child->getPath()
               );
            }
        }
        return $this->render('widgets/top_menu.html.twig', compact('menu'));
    }

}