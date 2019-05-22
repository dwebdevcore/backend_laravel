<?php

namespace App\Ownership;

use App\Mail\OwnershipRequested;
use App\Notifications\NotifyOwnershipRequested;
use App\Repositories\BusinessAttributesRepository;
use App\Repositories\OwnershipRequestsRepository;
use App\Repositories\OwnershipsRepository;
use App\Transformers\PhoneTransformer;
use Illuminate\Mail\Mailer;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\ChannelManager;
use NotificationChannels\Twilio\TwilioChannel;

class OwnershipService
{
    const METHOD_EMAIL = 'email';

    const METHOD_PHONE = 'phone';

    /**
     * @var BusinessAttributesRepository
     */
    private $businessAttributesRepository;

    /**
     * @var OwnershipsRepository
     */
    private $ownershipsRepository;

    /**
     * @var OwnershipRequestsRepository
     */
    private $ownershipRequestsRepository;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var ChannelManager
     */
    private $notification;

    /**
     * @var PhoneTransformer
     */
    private $phoneTransformer;

    public function __construct(
        BusinessAttributesRepository $businessAttributesRepository,
        OwnershipsRepository $ownershipsRepository,
        OwnershipRequestsRepository $ownershipRequestsRepository,
        Mailer $mailer,
        ChannelManager $notification,
        PhoneTransformer $phoneTransformer
    ) {
        $this->businessAttributesRepository = $businessAttributesRepository;
        $this->ownershipsRepository = $ownershipsRepository;
        $this->ownershipRequestsRepository = $ownershipRequestsRepository;
        $this->mailer = $mailer;
        $this->notification = $notification;
        $this->phoneTransformer = $phoneTransformer;
    }

    public function getAvailableMethods(int $businessId)
    {
        $methods = [];

        $attributes = $this->businessAttributesRepository->getAttributesValues(
            $businessId,
            self::METHOD_EMAIL,
            self::METHOD_PHONE
        );

        foreach($attributes as $attribute) {
            $methods[$attribute['key']][] = $attribute['value'];
        }

        return $methods;
    }

    /**
     * @param int $userId
     * @param int $businessId
     * @param string $method
     * @param string $address
     * @param array $userInfo
     * @return bool
     * @throws \Exception
     */
    public function requestOwnership(int $userId, int $businessId, string $method, string $address, array $userInfo = [])
    {
        if ($this->ownershipRequestsRepository->activeRequestExists($userId)) {
            throw new \Exception('An active ownership request already exists.');
        }

        $methods = $this->getAvailableMethods($businessId);

        if (!array_key_exists($method, $methods)) {
            throw new \Exception('Unknown ownership method requested.');
        }

        if (!in_array($address, $methods[$method])) {
            throw new \Exception('Unknown address requested.');
        }

        if ($method === self::METHOD_EMAIL) {
            return $this->requestOwnershipViaEmail($userId, $businessId, $address, $userInfo);
        }

        if ($method === self::METHOD_PHONE) {
            return $this->requestOwnershipViaPhone($userId, $businessId, $address, $userInfo);
        }

        return false;
    }

    /**
     * @param int $userId
     * @param int $businessId
     * @param string $token
     * @return bool
     * @throws \Exception
     */
    public function confirmOwnership(int $userId, int $businessId, string $token)
    {
        $ownershipRequest = $this->ownershipRequestsRepository->getActiveRequest($userId, $businessId);

        if (!$ownershipRequest) {
            throw new \Exception('An active ownership request not found for this business.');
        }

        if ($ownershipRequest['token'] !== $token) {
            throw new \Exception('Invalid token.');
        }

        $this->ownershipRequestsRepository->confirmRequest($ownershipRequest);

        $this->ownershipsRepository->create($userId, $businessId, $ownershipRequest['id']);

        return true;
    }

    /**
     * @param int $userId
     * @param int $businessId
     * @param string $email
     * @param array $userInfo
     * @return bool
     * @throws \Exception
     */
    private function requestOwnershipViaEmail(int $userId, int $businessId, string $email, array $userInfo = [])
    {
        $token = bin2hex(random_bytes(32));

        $this->ownershipRequestsRepository->create($userId, $businessId, self::METHOD_EMAIL, $email, $token, $userInfo);

        $this->mailer->to($email)->send(new OwnershipRequested($token));

        return true;
    }

    /**
     * @param int $userId
     * @param $businessId
     * @param string $phone
     * @param array $userInfo
     * @return bool
     */
    private function requestOwnershipViaPhone(int $userId, $businessId, string $phone, array $userInfo = [])
    {
        $phone = $this->phoneTransformer->toE164($phone);

        $code = mt_rand(1000, 9999);

        $this->ownershipRequestsRepository->create($userId, $businessId, self::METHOD_PHONE, $phone, $code, $userInfo);

        $this->notification->send(
            (new AnonymousNotifiable)->route(TwilioChannel::class, $phone),
            new NotifyOwnershipRequested($code)
        );

        return true;
    }

    /**
     * @param int $userId
     * @param int $businessId
     * @return \App\Models\OwnershipRequest|null
     */
    public function getUserOwnershipRequest(int $userId, int $businessId) {
        return $this->ownershipRequestsRepository->getActiveRequest($userId, $businessId);
    }
}
