<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Subscriber;
use AppBundle\Entity\Category;
use AppBundle\Helper\SortHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminSubscriberController extends Controller {

    /**
     * @Route("/admin/subscriber", name="admin.subscriber")
     */
    public function indexAction(Request $req) {
        return $this->render('admin/subscriber/index.html.twig', [
            'subscribers' => Subscriber::getAllOrdered(
                $req->get('sort_column'),
                $req->get('sort_direction')
            ),
            'sort' => new SortHelper($req),
        ]);
    }

    /**
     * @Route("/admin/subscriber/{id}/edit", name="admin.subscriber.edit")
     */
    public function editAction(Request $req, $id) {
        $subscriber = new Subscriber([ 'id' => $id ]);
        $subscriber->load();

        $form = $this->createFormBuilder($subscriber)
            ->add('name', 'text', [])
            ->add('email', 'email', [])
            ->add('categories', 'choice', [
                'choices' => Category::getAllKeyValue(),
                'multiple' => true,
                'attr' => [
                    'class' => 'ui fluid dropdown'
                ],
            ])
            ->getForm();

        $form->handleRequest($req);

        if ($req->isMethod('POST') && $form->isValid()) {
            $subscriber->save();
            return $this->redirectToRoute('admin.subscriber');
        }

        return $this->render('admin/subscriber/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/subscriber/{id}/delete", name="admin.subscriber.delete")
     */
    public function deleteAction(Request $req, $id) {
        $subscriber = new Subscriber([ 'id' => $id ]);
        $subscriber->delete();
        return $this->redirectToRoute('admin.subscriber');
    }

}
