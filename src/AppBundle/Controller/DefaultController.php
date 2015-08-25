<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Subscriber;
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class DefaultController extends Controller {

    /**
     * @Route("/", name="subscribe")
     */
    public function subscribeAction(Request $req) {
        $subscriber = new Subscriber();

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
            return $this->render('subscribe-successful.html.twig', []);
        }

        return $this->render('subscribe.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
