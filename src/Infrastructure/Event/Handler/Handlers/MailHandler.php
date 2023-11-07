<?php

namespace App\Infrastructure\Event\Handler\Handlers;

use App\Application\Service\Mail\Campaign\SendCampaignCreatedMail\SendCampaignCreatedMailCommand;
use App\Application\Service\Mail\Campaign\SendCampaignInvoiceMail\SendCampaignInvoiceMailCommand;
use App\Application\Service\Mail\Company\SendCompanyRegisterMail\SendCompanyRegisterMailCommand;
use App\Application\Service\Mail\Company\SendContactCreatedMail\SendContactCreatedMailCommand;
use App\Application\Service\Mail\Meeting\SendMeetingCanceledMail\SendMeetingCanceledMailCommand;
use App\Application\Service\Mail\Meeting\SendMeetingCreatedMail\SendMeetingCreatedMailCommand;
use App\Application\Service\Mail\Meeting\SendMeetingStartIn30MinutesMail\SendMeetingStartIn30MinutesMailCommand;
use App\Domain\Entity\Event\Campaign\CampaignCreated;
use App\Domain\Entity\Event\Company\CompanyCreated;
use App\Domain\Entity\Event\Contact\ContactCreated;
use App\Domain\Entity\Event\Meeting\Meeting30MinutesBeforeStartDateReached;
use App\Domain\Entity\Event\Meeting\MeetingCanceled;
use App\Domain\Entity\Event\Meeting\MeetingCreated;
use App\Domain\Entity\Event\Payment\PaymentInvoiceUrlSet;
use App\Domain\Entity\Event\User\UserCreated;
use App\Infrastructure\Event\Handler\AbstractEventHandle;
use App\Infrastructure\Event\Handler\EventHandlerInterface;
use Ramsey\Uuid\Uuid;

class MailHandler extends AbstractEventHandle implements EventHandlerInterface
{
    const EXCHANGE = "mail";

    const TYPES = [
        UserCreated::TYPE
    ];

    public function fire(string $type, $body, string $occurredOn): int
    {
        return 0;
    }

    public function isSubscribedTo(string $exchange, string $type): bool
    {
        return $exchange == self::EXCHANGE && in_array($type, self::TYPES);
    }
}