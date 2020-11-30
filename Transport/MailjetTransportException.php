<?php


namespace VSPoint\Mailjet\Transport;

use Symfony\Component\Mailer\Exception\TransportException;


class MailjetTransportException extends TransportException
{

    /**
     * @var array|null
     */
    public $response;
    
    /**
     * @var array|null
     */
    public $vars;

}