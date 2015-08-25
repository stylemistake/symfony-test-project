<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Subscriber;
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller {

    /**
     * @Route("/admin", name="admin")
     */
    public function indexAction(Request $req) {
        return $this->redirectToRoute('admin.subscriber');
    }

}
