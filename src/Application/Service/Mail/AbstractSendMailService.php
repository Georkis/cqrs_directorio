<?php

namespace App\Application\Service\Mail;

use App\Application\Exception\ErrorSendMailSendgridException;
use App\Domain\Entity\MailLog;
use App\Domain\Repository\MailLogRepository;
use App\Domain\ValueObject\MailType;
use SendGrid;
use SendGrid\Mail\Attachment;
use SendGrid\Mail\ClickTracking;
use SendGrid\Mail\From;
use SendGrid\Mail\Mail;
use SendGrid\Mail\OpenTracking;
use SendGrid\Mail\Personalization;
use SendGrid\Mail\To;
use SendGrid\Mail\TrackingSettings;

abstract class AbstractSendMailService
{

    private string $sendgridApiKey;
    private string $sender;
    private string $senderName;
    private bool $isProd;
    private array $devDomains;
    private MailLogRepository $mailLogRepository;

    public function __construct(
        MailLogRepository $mailLogRepository,
        string $sendgridApiKey,
        string $sender,
        string $senderName,
        string $env,
        array $devDomains
    )
    {
        $this->mailLogRepository = $mailLogRepository;
        $this->sendgridApiKey = $sendgridApiKey;
        $this->sender = $sender;
        $this->senderName = $senderName;
        $this->isProd = $env == "prod";
        $this->devDomains = $devDomains;
    }

    /**
     * @param string $template
     * @param string $recipient
     * @param string $name
     * @param array $substitutions
     * @param array $attachments
     * @param MailType $type
     * @throws ErrorSendMailSendgridException
     * @throws SendGrid\Mail\TypeException
     */
    protected function sendMail(
        MailType $type,
        string $template,
        string $recipient,
        string $name,
        array $substitutions = [],
        array $attachments = []
    )
    {
        if (!$this->isProd) {
            $isDevDomain = false;

            foreach ($this->devDomains as $devDomain) {
                if (str_ends_with($recipient, $devDomain)) {
                    $isDevDomain = true;
                    break;
                }
            }
            if (!$isDevDomain) {
                return;
            }
        }

        //dd($template, $recipient, $name, $substitutions);
        $sg = new SendGrid($this->sendgridApiKey);

        $mail = new Mail();

        $personalization0 = new Personalization();
        $personalization0->addTo(new To($recipient, $name));
        $mail->addPersonalization($personalization0);

        $mail->setFrom(new From($this->sender, $this->senderName));

        $mail->setTemplateId($template);

        foreach ($substitutions as $key => $substitution) {
            $mail->addSubstitution($key, $substitution);
        }

        if (count($attachments) > 0) {
            foreach ($attachments as $attachment) {
                $attachment0 = new Attachment();
                $attachment0->setContent(base64_encode(file_get_contents($attachment["path"])));
                $attachment0->setFilename($attachment["name"]);
                $attachment0->setType($attachment["type"]);
                $attachment0->setDisposition("attachment");
                $mail->addAttachment($attachment0);
            }
        }

        $tracking_settings = new TrackingSettings();

        $click_tracking = new ClickTracking();
        $click_tracking->setEnable(true);
        $click_tracking->setEnableText(false);
        $tracking_settings->setClickTracking($click_tracking);

        $open_tracking = new OpenTracking();
        $open_tracking->setEnable(true);
        $open_tracking->setSubstitutionTag("%open-track%");
        $tracking_settings->setOpenTracking($open_tracking);

        $mail->setTrackingSettings($tracking_settings);

        $request_body = $mail;

        $response = $sg->client->mail()->send()->post($request_body);
        if (!str_starts_with(haystack: $response->statusCode(), needle: "20")) {
            throw new ErrorSendMailSendgridException($response->statusCode() . ": " . $response->body());
        }

        try {
            $this->mailLogRepository->save(MailLog::create(
                template: $template,
                recipient: $recipient,
                name: $name,
                sender: $this->sender,
                senderName: $this->senderName,
                type: $type
            ));
        } catch (\Exception $e) {
        }
    }
}
