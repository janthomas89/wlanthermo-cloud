<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * History controller.
 *
 * @Route("/history")
 * @Security("has_role('ROLE_AUTHENTICATED')")
 */
class HistoryController extends Controller
{

    /**
     * Lists all past measurements entities.
     *
     * @Route("/", name="history")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Measurement')->findBy(
            array(),
            array('id'=>'DESC')
        );

        return array(
            'entities' => $entities
        );
    }
}
