<?php

namespace App\Controller\Admin;

use App\Entity\Color;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ColorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Color::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Название цвета'),
            TextField::new('colorPlural', 'Назание во мн. числе')->setRequired(true),
            TextField::new('code', 'Когд цвета')->setHelp('Например: #ffffff'),
            TextField::new('slug', 'Алиас')
        ];
    }

}
