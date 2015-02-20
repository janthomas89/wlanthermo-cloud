<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HeadbarController extends Controller
{
    public function indexAction($title)
    {
        return $this->render('AppBundle:Headbar:index.html.twig', array(
            'title' => $title
        ));
    }
}
