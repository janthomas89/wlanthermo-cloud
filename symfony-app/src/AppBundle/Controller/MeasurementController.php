<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Measurement;
use AppBundle\Entity\MeasurementProbe;
use AppBundle\Form\MeasurementType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
     * Creates a new Measurement entity.
     *
     * @Route("/new/{silent}", defaults={"silent" = false}, name="measurement_new")
     * @Method({"GET","POST"})
     * @Template("AppBundle:Measurement:new.html.twig")
     */
    public function newAction(Request $request)
    {
        $entity = new Measurement();

        $form = $this->createCreateForm($entity);

        if (!$request->get('silent') && $request->isMethod('POST')) {
            $form->handleRequest($request);
        }

        if (!$request->get('silent') && $request->isMethod('POST') && $form->isValid()) {
            $entity->setStart(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('measurement', array(
                'id' => $entity->getId()
            )));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

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
            throw $this->createNotFoundException('Unable to find Measurement entity.');
        }

        return array(
            'entity' => $entity
        );
    }

    /**
     * Creates a form to create a Measurement entity.
     *
     * @param Measurement $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Measurement $entity)
    {
        $request = $this->get('request');
        if ($this->get('request')->get('silent') || $request->isMethod('GET')) {
            $this->initMeasurement($entity);
        }

        $form = $this->createForm(new MeasurementType(), $entity, array(
            'action' => $this->generateUrl('measurement_new'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Speichern'));

        return $form;
    }

    private function initMeasurement(Measurement $measurement)
    {
        $measurement->setName('Grillen ' . date('d.m.Y'));

        /** @var Request $request */
        $request = $this->get('request');

        $formData = $request->request->get('appbundle_measurement');
        $deviceId = isset($formData['device']) ? (int)$formData['device'] : 0;
        $em = $this->getDoctrine()->getManager();

        if (!$deviceId) {
            $device = $em->getRepository('AppBundle:Device')->getOne();
        }

        if (!$device) {
            $device = $em->getRepository('AppBundle:Device')->find($deviceId);
        }

        if (!$device) {
            return;
        }

        $measurement->setDevice($device);

        foreach ($device->getProbes() as $probe) {
            $tmpProbe = new MeasurementProbe();
            $tmpProbe->setProbe($probe);
            $tmpProbe->setColor($probe->getDefaultColor());
            $tmpProbe->setName($probe->getDefaultName());
            $tmpProbe->setMin(MeasurementProbe::DEFAULT_MIN);
            $tmpProbe->setMax(MeasurementProbe::DEFAULT_MAX);
            $measurement->addProbe($tmpProbe);
        }
    }

    /**
     * Displays a form to edit an existing Measurement entity.
     *
     * @Route("/{id}/edit", name="measurement_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction($id)
    {
        $request = $this->get('request');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Measurement')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Measurement entity.');
        }

        $originalProbes = new ArrayCollection();
        foreach ($entity->getProbes() as $probe) {
            $originalProbes->add($probe);
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($request->isMethod('POST') && $editForm->isValid()) {
            foreach ($originalProbes as $probe) {
                if (false === $entity->getProbes()->contains($probe)) {
                    $em->remove($probe);
                }
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('measurement', array(
                'id' => $entity->getId()
            )));
        }

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Device entity.
     *
     * @param Device $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Device $entity)
    {
        $form = $this->createForm(new MeasurementType(), $entity, array(
            'action' => $this->generateUrl('measurement_edit', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Speichern'));

        return $form;
    }
}
