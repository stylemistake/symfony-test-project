<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Subscriber;
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminCategoryController extends Controller {

    /**
     * @Route("/admin/category", name="admin.category")
     */
    public function indexAction(Request $req) {
        $category = new Category();
        $form = $this->createFormBuilder($category)
            ->add('name', 'text', [])
            ->getForm();
        $form_clean = clone $form;
        $form->handleRequest($req);

        if ($req->isMethod('POST') && $form->isValid()) {
            $category->save();
            // Clear the form
            $form = $form_clean;
        }

        return $this->render('admin/category/index.html.twig', [
            'categories' => Category::getAll(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/category/{id}/delete", name="admin.category.delete")
     */
    public function deleteAction(Request $req, $id) {
        $category = new Category([ 'id' => $id ]);
        $category->delete();
        return $this->redirectToRoute('admin.category');
    }

}
