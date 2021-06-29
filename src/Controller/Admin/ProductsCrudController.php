<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class ProductsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Products::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            Field::new('id','ID')->onlyOnIndex(),
            TextField::new('name','Название'),
            AssociationField::new('category_id', 'Категория товара'),
            AssociationField::new('color', 'Цвет'),
            TextField::new('path', 'Алиас')->setHelp('обязательно / в конце. Пример: stoleshnicy/iz-mramora/stoleshnica1/'),
            //UrlField::new('path','URL')->setHelp('обязательно / в конце. Пример: stoleshnicy/iz-mramora/stoleshnica1/'),
            NumberField::new('price', 'Цена'),
            AssociationField::new('measure', 'Цена за')->hideOnIndex(),
            ImageField::new('image', 'Картинка товара')->setUploadDir('/public/uploads/products')->setBasePath('/uploads/products/'),
            TextField::new('metaTitle','Title'),
            TextField::new('meta_description', 'Meta описание')->hideOnIndex(),
            TextEditorField::new('cardText', 'Текст карточки')->hideOnIndex(),
        ];
    }

}
