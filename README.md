Mailjet Mailer
==============

Provides Mailjet integration for Symfony Mailer.

## Usage

1. Installation via composer
```bash
composer require vs-point/symfony-mailjet-mailer
```

2. Register in services.yaml

```yaml
VSPoint\Mailjet\Transport\MailjetTransportFactory:
   tags:
      - mailer.transport_factory
```

3.  Provide configuration in .env file
```
{JETMAILER_NAME}=mailjet://{public key}:{private key}@mailjet
```

### Send normail email
```php
$dsn = 'mailjet://{public key}:{private key}@mailjet';

$transport = Transport::fromDsn($dsn);
$mailer = new Mailer($transport);
$email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');
$mailer->send($email);
```

### Send templated email
```php
$dsn = 'mailjet://{public key}:{private key}@mailjet';

$transport = Transport::fromDsn($dsn);
$mailer = new Mailer($transport);
$email = (new MailjetTemplateEmail(123456789,['variable'=>'value']))
            ->from('my@mail.com');
$mailer->send($email);
```
