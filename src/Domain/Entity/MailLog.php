<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\MailType;
use Ramsey\Uuid\Uuid;

class MailLog extends AbstractUuidEntity
{
    private string $template;
    private string $recipient;
    private string $name;
    private string $sender;
    private string $senderName;
    private MailType $type;

    /**
     * @param string $template
     * @param string $recipient
     * @param string $name
     * @param string $sender
     * @param string $senderName
     * @param MailType $type
     * @return static
     */
    public static function create(
        string $template,
        string $recipient,
        string $name,
        string $sender,
        string $senderName,
        MailType $type
    ): self
    {
        $e = new static(
            Uuid::uuid4()
        );

        $e->template = $template;
        $e->recipient = $recipient;
        $e->name = $name;
        $e->sender = $sender;
        $e->senderName = $senderName;
        $e->type = $type;

        return $e;
    }


    /**
     * @return string
     */
    public function template(): string
    {
        return $this->template;
    }

    /**
     * @return string
     */
    public function recipient(): string
    {
        return $this->recipient;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function sender(): string
    {
        return $this->sender;
    }

    /**
     * @return string
     */
    public function senderName(): string
    {
        return $this->senderName;
    }

    /**
     * @return MailType
     */
    public function type(): MailType
    {
        return $this->type;
    }

}
