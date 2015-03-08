<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NavigationController extends Controller
{
    public function indexAction($route, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $numDevices = $em->getRepository('AppBundle:Device')->countAll();
        $numMeasurements = $em->getRepository('AppBundle:Measurement')->countAll();
        $activeMeasurements = $em->getRepository('AppBundle:Measurement')->getActive();

        return $this->render('AppBundle:Navigation:index.html.twig', array(
            'route' => $route,
            'id' => $id,
            'num_measurements' => $numMeasurements,
            'active_measurements' => $activeMeasurements,
            'num_devices' => $numDevices,
        ));
    }
}