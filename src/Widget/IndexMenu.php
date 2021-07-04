<?php


namespace App\Widget;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ContentRepository;


class IndexMenu extends AbstractController
{

    public function show(ContentRepository $contentRepository){
        $menuItems = $contentRepository->findBy(
            [
                'index_menu' => 1,
            ]
        );
        return $this->render('widgets/index_menu.html.twig', compact('menuItems'));

    }
}