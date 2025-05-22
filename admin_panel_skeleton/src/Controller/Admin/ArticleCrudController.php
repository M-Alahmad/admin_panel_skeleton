<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
// Felder importieren, die du tatsächlich nutzt:
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Title'),
            TextareaField::new('content', 'Content'),
            DateTimeField::new('createdAt', 'Created At')->onlyOnIndex(),
            DateTimeField::new('updatedAt', 'Updated At')->hideOnForm(),
        ];
    }
}
