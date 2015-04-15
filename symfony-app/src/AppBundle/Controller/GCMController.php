<?php

namespace AppBundle\Controller;


use AppBundle\Entity\GCMSubscription;
use AppBundle\Entity\GCMSubscriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Controller for GCM subscriptions.
 *
 * @Route("/gcm")
 * @Security("has_role('ROLE_ADMIN')")
 */
class GCMController extends Controller
{
    /**
     * Stores a GCM subscription.
     *
     * @Route("/subscribe", name="gcm_subscribe")
     * @Method("POST")
     */
    public function subscribeAction(Request $request)
    {
        $registrationId = $request->request->get('registrationId');

        if (!$registrationId) {
            return $this->createResponse('Please provide a registration id',  404);
        }

        $em = $this->getDoctrine()->getManager();

        /** @var GCMSubscriptionRepository $repo */
        $repo = $em->getRepository('AppBundle:GCMSubscription');

        if ($repo->findOneBy(['registrationId' => $registrationId])) {
            return $this->createResponse('Already subscribed',  200);
        }

        $subscription = new GCMSubscription();
        $subscription->setRegistrationId($registrationId);

        $em->persist($subscription);
        $em->flush();

        return $this->createResponse('Subscription created');
    }

    /**
     * Removes a GCM subscription.
     *
     * @Route("/unsubscribe", name="gcm_unsubscribe")
     * @Method("POST")
     */
    public function unsubscribeAction(Request $request)
    {
        $registrationId = $request->request->get('registrationId');

        if (!$registrationId) {
            return $this->createResponse('Please provide a registration id',  404);
        }

        $em = $this->getDoctrine()->getManager();

        /** @var GCMSubscriptionRepository $repo */
        $repo = $em->getRepository('AppBundle:GCMSubscription');

        $subscription = $repo->findOneBy(['registrationId' => $registrationId]);

        if (!$subscription) {
            return $this->createResponse('Subscription not found',  404);
        }

        $em->remove($subscription);
        $em->flush();

        return $this->createResponse('Subscription removed');
    }

    /**
     * Creates a JSON response
     *
     * @param $msg
     * @param int $status
     * @return JsonResponse
     * @throws \Exception
     */
    private function createResponse($msg, $status = 200)
    {
        $resp = new JsonResponse();
        $resp->setStatusCode($status);
        $resp->setData([
            'msg' => $msg
        ]);

        return $resp;
    }
}
