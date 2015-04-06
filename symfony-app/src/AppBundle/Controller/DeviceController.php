<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Probe;
use AppBundle\Service\DeviceAPIService;
use AppBundle\Service\DeviceAPIServiceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Device;
use AppBundle\Form\DeviceType;

/**
 * Device controller.
 *
 * @Route("/devices")
 * @Security("has_role('ROLE_ADMIN')")
 */
class DeviceController extends Controller
{

    /**
     * Lists all Device entities.
     *
     * @Route("/", name="devices")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Device')->findAll();

        return array(
            'entities' => $entities,
            'delete_forms' => $this->createDeleteFormViews($entities),
            'shut_down_forms' => $this->createShutDownFormViews($entities),
            'status' => $this->getDeviceStatus($entities),
        );
    }

    /**
     * Creates a new Device entity.
     *
     * @Route("/", name="devices_create")
     * @Method("POST")
     * @Template("AppBundle:Device:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Device();

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            /** @var DeviceAPIServiceInterface $service */
            $service = $this->get('device_api_service');
            $service->saveProbeConfig($entity);

            return $this->redirect($this->generateUrl('devices'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Device entity.
     *
     * @param Device $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Device $entity)
    {
        $form = $this->createForm(new DeviceType(), $entity, array(
            'action' => $this->generateUrl('devices_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Speichern'));

        return $form;
    }

    /**
     * Displays a form to create a new Device entity.
     *
     * @Route("/new", name="devices_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Device();
        $entity->addDefaultProbes();

        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Device entity.
     *
     * @Route("/{id}/edit", name="devices_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Device')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Device entity.');
        }

        $editForm = $this->createEditForm($entity);

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
        $form = $this->createForm(new DeviceType(), $entity, array(
            'action' => $this->generateUrl('devices_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Speichern'));

        return $form;
    }
    /**
     * Edits an existing Device entity.
     *
     * @Route("/{id}", name="devices_update")
     * @Method("PUT")
     * @Template("AppBundle:Device:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Device')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Device entity.');
        }

        $originalProbes = new ArrayCollection();
        foreach ($entity->getProbes() as $probe) {
            $originalProbes->add($probe);
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            foreach ($originalProbes as $probe) {
                if (false === $entity->getProbes()->contains($probe)) {
                    $em->remove($probe);
                }
            }

            $em->persist($entity);
            $em->flush();

            /** @var DeviceAPIServiceInterface $service */
            $service = $this->get('device_api_service');
            $service->saveProbeConfig($entity);

            return $this->redirect($this->generateUrl('devices'));
        }

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Device entity.
     *
     * @Route("/{id}", name="devices_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Device')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Device entity.');
            }

            /* Delete related probes as well */
            foreach ($entity->getProbes() as $probe) {
                $em->remove($probe);
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('devices'));
    }

    /**
     * Creates a form to delete a Device entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('devices_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Gerät löschen'))
            ->getForm()
        ;
    }

    /**
     * Creates a form to shut down a Device.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createShutDownForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('device_shut_down', array('id' => $id)))
            ->setMethod('POST')
            ->add('restart')
            ->add('shutDown')
            ->getForm()
            ;
    }

    /**
     * Creatres delete form views for the given entities.
     *
     * @param array[Device] $entities
     * @return array[\Symfony\Component\Form\FormView]
     */
    private function createDeleteFormViews(array $entities)
    {
        $deleteFormViews = array();

        /** @var Device $device */
        foreach ($entities as $device) {
            $deleteForm = $this->createDeleteForm($device->getId());
            $deleteFormViews[$device->getId()] = $deleteForm->createView();
        }

        return $deleteFormViews;
    }

    /**
     * Creates shut down form views for the given entities.
     *
     * @param array[Device] $entities
     * @return array[\Symfony\Component\Form\FormView]
     */
    private function createShutDownFormViews(array $entities)
    {
        $formViews = array();

        /** @var Device $device */
        foreach ($entities as $device) {
            $form = $this->createShutDownForm($device->getId());
            $formViews[$device->getId()] = $form->createView();
        }

        return $formViews;
    }

    /**
     * Returns the status for the given devices.
     *
     * @param array[Device] $entities
     * @return array[bool]
     */
    private function getDeviceStatus(array $entities)
    {
        $status = [];

        /** @var DeviceAPIServiceInterface $service */
        $service = $this->get('device_api_service');

        /** @var Device $device */
        foreach ($entities as $device) {
            $status[$device->getId()] = $service->checkConnectivity($device)
                && $service->checkAuthentication($device);
        }

        return $status;
    }

    /**
     * Stops the measurement and shuts down the device.
     *
     * @Route("/{id}/shutDown", name="device_shut_down")
     * @Method("POST")
     * @Template()
     */
    public function shutDownAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Device')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Device entity.');
        }

        $redirectResp = $this->redirect($this->generateUrl('devices'));

        /** @var DeviceAPIService $deviceAPI */
        $deviceAPI = $this->get('device_api_service');

        if (!$deviceAPI->checkConnectivity($entity)
            || !$deviceAPI->checkAuthentication($entity)
        ) {
            return $redirectResp;
        }

        /** @var Request $request */
        $request = $this->get('request');
        if ($request->request->get('form[restart]', false, true)) {
            $deviceAPI->restart($entity);
        } else if ($request->request->get('form[shutDown]', false, true)) {
            $deviceAPI->shutdown($entity);
        }

        return $redirectResp;
    }
}
