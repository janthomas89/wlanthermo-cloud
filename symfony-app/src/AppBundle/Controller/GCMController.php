<?php

namespace AppBundle\Controller;


use AppBundle\Entity\GCMSubscription;
use AppBundle\Entity\GCMSubscriptionRepository;
use AppBundle\Entity\Notification;
use Doctrine\ORM\EntityManager;
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
 */
class GCMController extends Controller
{
    /**
     * Stores a GCM subscription.
     *
     * @Route("/subscribe", name="gcm_subscribe")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function subscribeAction(Request $request)
    {
        $registrationId = $request->request->get('registrationId');

        if (!$registrationId) {
            return $this->createResponse(['msg' => 'Please provide a registration id'],  404);
        }

        $em = $this->getDoctrine()->getManager();

        /** @var GCMSubscriptionRepository $repo */
        $repo = $em->getRepository('AppBundle:GCMSubscription');

        if ($repo->findOneBy(['registrationId' => $registrationId])) {
            return $this->createResponse(['msg' => 'Already subscribed'],  200);
        }

        $subscription = new GCMSubscription();
        $subscription->setRegistrationId($registrationId);

        $em->persist($subscription);
        $em->flush();

        return $this->createResponse(['msg' => 'Subscription created']);
    }

    /**
     * Removes a GCM subscription.
     *
     * @Route("/unsubscribe", name="gcm_unsubscribe")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function unsubscribeAction(Request $request)
    {
        $registrationId = $request->request->get('registrationId');

        if (!$registrationId) {
            return $this->createResponse(['msg' => 'Please provide a registration id'],  404);
        }

        $em = $this->getDoctrine()->getManager();

        /** @var GCMSubscriptionRepository $repo */
        $repo = $em->getRepository('AppBundle:GCMSubscription');

        $subscription = $repo->findOneBy(['registrationId' => $registrationId]);

        if (!$subscription) {
            return $this->createResponse(['msg' => 'Subscription not found'],  404);
        }

        $em->remove($subscription);
        $em->flush();

        return $this->createResponse(['msg' => 'Subscription removed']);
    }

    /**
     * Returns all Notifications for the given registration Id.
     *
     * @Route("/get/{registrationId}", name="gcm_get")
     * @Method("GET")
     */
    public function getNotificationsAction($registrationId)
    {
        if (!$registrationId) {
            return $this->createResponse(['msg' => 'Please provide a registration id'],  404);
        }

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var GCMSubscriptionRepository $repo */
        $repo = $em->getRepository('AppBundle:GCMSubscription');

        /** @var GCMSubscription $subscription */
        $subscription = $repo->findOneBy(['registrationId' => $registrationId]);

        if (!$subscription) {
            return $this->createResponse(['msg' => 'Subscription not found'],  404);
        }

        $response = $this->createResponse([
            'notifications' => array_map(function(Notification $notification) {
                return [
                    'subject' => $notification->getSubject(),
                    'msg' => $notification->getMsg(),
                ];
            }, $subscription->getNotifications())
        ]);

        /* Reset notifications */
        $subscription->setPayload(null);
        $em->persist($subscription);
        $em->flush();

        return $response;
    }

    /**
     * Creates a JSON response
     *
     * @param $data
     * @param int $status
     * @return JsonResponse
     * @throws \Exception
     */
    private function createResponse($data, $status = 200)
    {
        $resp = new JsonResponse();
        $resp->setStatusCode($status);
        $resp->setData($data);

        return $resp;
    }
}
