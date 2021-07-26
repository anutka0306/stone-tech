<?php

namespace App\Controller\Admin;

use App\Entity\StoneProduct;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use PhpParser\Node\Scalar\MagicConst\File;

class StoneProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return StoneProduct::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('slug'),
            NumberField::new('weight'),
            ImageField::new('small_img')->setUploadDir('/public/images/stone/catalog')->setBasePath('images/stone/catalog/'),
            ImageField::new('big_img')->setUploadDir('/public/images/stone/big')->setBasePath('images/stone/big/'),
            AssociationField::new('parent'),
            AssociationField::new('color'),
            AssociationField::new('country'),
        ];
    }

}
