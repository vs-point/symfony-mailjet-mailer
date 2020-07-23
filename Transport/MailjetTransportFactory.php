<?php

namespace VSPoint\Mailjet\Transport;

use Symfony\Component\Mailer\Transport\AbstractTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\TransportInterface;

final class MailjetTransportFactory extends AbstractTransportFactory
{
    public function create(Dsn $dsn): TransportInterface
    {
        $user = $this->getUser($dsn);
        $password = $this->getPassword($dsn);

        return new MailjetTransport($user, $password, $dsn->getHost(), $this->dispatcher, $this->logger );
    }

    protected function getSupportedSchemes(): array
    {
        return ['mailjet'];
    }
}
