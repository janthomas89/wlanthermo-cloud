<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class IndexController
 * @package AppBundle\Controller
 *
 * @Security("has_role('ROLE_AUTHENTICATED')")
 */
class IndexController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $activeMeasurements = $em->getRepository('AppBundle:Measurement')->getActive();

        if (count($activeMeasurements) > 0) {
            $latest = reset($activeMeasurements);

            return $this->redirect($this->generateUrl('measurement', array(
                'id' => $latest->getId()
            )));
        }

        return $this->redirect($this->generateUrl('history'));
    }
}
