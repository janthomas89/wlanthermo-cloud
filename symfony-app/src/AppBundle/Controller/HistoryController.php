<?php

namespace AppBundle\Controller;

use AppBundle\Entity\MeasurementRepository;
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
     * @Route("/{page}", defaults={"page" = 1}, name="history", requirements={
     *     "page": "\d+|"
     * })
     * @Method("GET")
     * @Template()
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var MeasurementRepository $repo */
        $repo = $em->getRepository('AppBundle:Measurement');

        $entities = $repo->history($page);
        $pages = ceil(count($entities) / $repo->getLimit());

        if ($page > 1 && count($entities->getIterator()) == 0) {
            throw $this->createNotFoundException();
        }

        return array(
            'page' => $page,
            'pages' => $pages,
            'entities' => $entities
        );
    }
}
