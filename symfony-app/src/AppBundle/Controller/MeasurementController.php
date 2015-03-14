<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Measurement controller.
 *
 * @Route("/measurement")
 * @Security("has_role('ROLE_AUTHENTICATED')")
 */
class MeasurementController extends Controller
{

    /**
     * Lists all past measurements entities.
     *
     * @Route("/{id}", name="measurement")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Measurement')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Device entity.');
        }

        return array(
            'entity' => $entity
        );
    }
}
