<?php

namespace App\Mail;

use Mailjet\Client;
use Mailjet\Resources;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\MessageConverter;

class MailjetTransport extends AbstractTransport
{
    protected Client $client;

    public function __construct(string $apiKey, string $apiSecret)
    {
        parent::__construct();

        $this->client = new Client($apiKey, $apiSecret, true, ['version' => 'v3.1']);
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $payload = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $email->getFrom()[0]->getAddress(),
                        'Name' => $email->getFrom()[0]->getName() ?? '',
                    ],
                    'To' => $this->getRecipients($email->getTo()),
                    'Subject' => $email->getSubject(),
                    'TextPart' => $email->getTextBody(),
                    'HTMLPart' => $email->getHtmlBody(),
                ]
            ]
        ];

        if (!empty($email->getCc())) {
            $payload['Messages'][0]['Cc'] = $this->getRecipients($email->getCc());
        }

        if (!empty($email->getBcc())) {
            $payload['Messages'][0]['Bcc'] = $this->getRecipients($email->getBcc());
        }

        $response = $this->client->post(Resources::$Email, ['body' => $payload]);

        if (!$response->success()) {
            throw new \Exception('Mailjet API error: ' . json_encode($response->getData()));
        }
    }

    protected function getRecipients(array $addresses): array
    {
        return array_map(function (Address $address) {
            return [
                'Email' => $address->getAddress(),
                'Name' => $address->getName() ?? '',
            ];
        }, $addresses);
    }

    public function __toString(): string
    {
        return 'mailjet';
    }
}
