<?php

namespace App\Controller\Admin;

use App\Entity\StoneCatalog;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class StoneCatalogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return StoneCatalog::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Название')->setRequired(true),
            TextField::new('slug', 'Алиас')->setRequired(true),
            TextEditorField::new('description', 'Описание')->setRequired(true),
            DateTimeField::new('updated')->hideOnIndex(),
        ];
    }

}
