<?php

namespace VSPoint\Mailjet\Transport;

use Mailjet\Client;
use Mailjet\Resources;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Mime\Email;

class MailjetTransport extends AbstractTransport
{
    /**
     * @var \Mailjet\Client
     */
    private $mj;

    public function __construct(
        string $publicKey,
        string $privateKey,
        string $url,
        EventDispatcherInterface $dispatcher,
        LoggerInterface $logger
    ){
        $this->mj = new Client($publicKey, $privateKey, true, [
            'version' => 'v3.1',
            'url' => $url,
        ]);

        parent::__construct($dispatcher, $logger);
    }

    protected function doSend(SentMessage $message): void
    {
        /** @var Email $email */
        $email = $message->getOriginalMessage();

        $subject = $email->getHeaders()->has('subject') ? $email->getHeaders()->get('subject')->getBody() :'' ;

        $messageArray = [];

        $messageArray['To'] = array_map(
            function ($recipient){
                return $this->fromAddress($recipient);
            },
            $message->getEnvelope()->getRecipients()
        );

        if ($email instanceof MailjetTemplateEmail){
            if ($email->isEmailSet()){
                $messageArray['From'] = $this->fromAddress($message->getEnvelope()->getSender());
            }
            if ($subject !== MailjetTemplateEmail::EMPTY_SUBJECT){
                $messageArray['Subject'] = $subject;
            }

            $messageArray['TemplateID'] = $email->getTemplateId();
            $messageArray['TemplateLanguage'] = true;
            $messageArray['Variables'] = $email->getVariables();
        } else {
            $messageArray['From'] = $this->fromAddress($message->getEnvelope()->getSender());
            $messageArray['HTMLPart'] = $email->getHtmlBody();
            $messageArray['Subject'] = $subject;
        }

        if (!empty($email->getAttachments())) {

            $messageArray['Attachments'] = array_map(function (DataPart $data){

                $headers = $data->getPreparedHeaders();

                $contentType = $headers->get('content-type');
                if ($contentType !== null){
                    $parts = explode('=',$contentType->getBodyAsString());
                    $filename = end($parts);
                } else {
                    $filename = $data->getContentId();
                }

                return [
                    "ContentType"=> $data->getMediaType().'/'.$data->getMediaSubtype(),
                    "Filename"=> $filename,
                    "Base64Content"=> $data->bodyToString(),
                ];
            }, $email->getAttachments());
        }

        $body = [
            'body' => [
                'Messages' => [
                    $messageArray
                ]
            ]
        ];

        $response = $this->mj->post(Resources::$Email, $body);

        if (!$response->success()){
            throw new TransportException(
                $response->getStatus() .' '.$response->getReasonPhrase()
            );
        }
    }

    private function fromAddress(Address $address): array {
        return [
            'Email' => $address->getAddress(),
            'Name' => $address->getName(),
        ];
    }

    public function __toString(): string
    {
        return '';
    }
}
