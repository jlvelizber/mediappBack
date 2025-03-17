<?php
namespace App\Services;

use GuzzleHttp\Client;

class WhatsappService
{
    protected $client;
    protected $accessToken;
    protected $phoneNumberId;

    public function __construct()
    {
        $this->client = new Client();
        $this->accessToken = env('WHATSAPP_ACCESS_TOKEN'); // Desde .env
        $this->phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID'); // ID de WhatsApp Cloud API
    }

    public function sendMessage($to, $template, $parameters = [])
    {
        $url = "https://graph.facebook.com/v18.0/{$this->phoneNumberId}/messages";

        $body = [
            "messaging_product" => "whatsapp",
            "to" => $to,
            "type" => "template",
            "template" => [
                "name" => $template,
                "language" => ["code" => "es"],
                "components" => [
                    [
                        "type" => "body",
                        "parameters" => array_map(fn($param) => ["type" => "text", "text" => $param], $parameters)
                    ]
                ]
            ]
        ];

        $response = $this->client->post($url, [
            'headers' => [
                'Authorization' => "Bearer {$this->accessToken}",
                'Content-Type' => 'application/json',
            ],
            'json' => $body,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}