Mailjet Mailer
==============

Provides Mailjet integration for Symfony Mailer.

## Usage
1. install

2. register in services.yaml
    ```    
    VSPoint\Mailjet\Transport\MailjetTransportFactory:
       tags:
           - mailer.transport_factory
    ```
3.  provide configuration in .env file
    ```
    {JETMAILER_NAME}=mailjet://{public key}:{private key}@baf 
    ```