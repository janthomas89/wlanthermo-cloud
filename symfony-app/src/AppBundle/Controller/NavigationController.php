<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NavigationController extends Controller
{
    public function indexAction($route)
    {
        /* Number of all devices */
        $em = $this->getDoctrine()->getManager();
        $numDevices = $em->getRepository('AppBundle:Device')->countAll();

        return $this->render('AppBundle:Navigation:index.html.twig', array(
            'route' => $route,
            'num_devices' => $numDevices,
        ));
    }
}