<?php

namespace AppBundle\Service;

use AppBundle\Entity\GCMSubscription;
use Buzz\Browser;
use Buzz\Message\Request;
use Psr\Log\LoggerInterface;

/**
 * Class GoogleCloudMessagingService
 * Service for sending Google Cloud Messaging notifications.
 *
 * @package AppBundle\Service
 */
class GoogleCloudMessagingService
{
    const API_ENDPOINT = 'https://android.googleapis.com/gcm/send';

    /** @var string */
    private $apiKey;

    /** @var Browser */
    private $browser;

    /** @var LoggerInterface */
    private $logger;

    /**
     * Instantiates the device api.
     *
     * @param string $apiKey
     * @param Browser $browser
     * @param LoggerInterface $logger
     */
    public function __construct($apiKey, Browser $browser, LoggerInterface $logger)
    {
        $this->apiKey = $apiKey;
        $this->browser = $browser;
        $this->logger = $logger;
    }

    /**
     * Sends a notofication for a single registration id.
     *
     * @param GCMSubscription $subscription
     * @param array $payload
     * @return array
     */
    public function send(GCMSubscription $subscription, array $payload = [])
    {
        $request = new Request('POST', '', self::API_ENDPOINT);
        $request->setHeaders([
            'Authorization' => 'key=' . $this->apiKey,
            'Content-Type' => 'application/json',
        ]);

        $payload['registration_ids'] = [$subscription->getRegistrationId()];
        $request->setContent(json_encode($payload));

        $this->logger->debug(var_export($request, true));

        $response = $this->browser->send($request);

        $this->logger->debug(var_export($response, true));

        return json_decode(
            $response->getContent(),
            true
        );
    }
} 