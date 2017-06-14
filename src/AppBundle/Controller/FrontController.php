<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * Index page
     */
    public function indexAction()
    {
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $products = $entityManager->getRepository('AppBundle:Product')->findAllOrderBy(['price' => 'ASC']);
        return $this->render('front/index.html.twig', ['products' => $products, 'back' => false]);
    }
}
