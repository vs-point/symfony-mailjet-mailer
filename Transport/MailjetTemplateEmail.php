<?php


namespace VSPoint\Mailjet\Transport;


use Symfony\Component\Mime\Email;

class MailjetTemplateEmail extends Email
{
    public const EMPTY_SUBJECT = '';

    /** @var bool */
    private $isEmailSet;

    /** @var int */
    private $templateId;

    /** @var array */
    private $variables;

    public function __construct(int $templateId, array $variables)
    {
        parent::__construct();
        $this->templateId = $templateId;
        $this->variables = $variables;
        $this->isEmailSet = false;
    }

    public function getTemplateId(): int
    {
        return $this->templateId;
    }

    public function getVariables(): array
    {
        return $this->variables;
    }

    public function isEmailSet(): bool
    {
        return $this->isEmailSet;
    }

    public function setIsEmailSet(bool $isEmailSet): void
    {
        $this->isEmailSet = $isEmailSet;
    }
}