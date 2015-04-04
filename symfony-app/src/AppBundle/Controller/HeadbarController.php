<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HeadbarController extends Controller
{
    public function indexAction($route, $id, $title)
    {
        return $this->render('AppBundle:Headbar:index.html.twig', array(
            'route' => $route,
            'id' => $id,
            'title' => $title
        ));
    }
}
