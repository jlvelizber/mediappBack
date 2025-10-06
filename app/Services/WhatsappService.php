<?php
namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

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
        // Validar que tenemos las credenciales necesarias
        if (!$this->accessToken || !$this->phoneNumberId) {
            Log::error('WhatsApp credentials not configured', [
                'access_token' => $this->accessToken ? 'set' : 'missing',
                'phone_number_id' => $this->phoneNumberId ? 'set' : 'missing'
            ]);
            return false;
        }

        // Formatear número de teléfono (remover + y espacios)
        $formattedPhone = preg_replace('/[^0-9]/', '', $to);
        
        $url = "https://graph.facebook.com/v22.0/{$this->phoneNumberId}/messages";

        $body = [
            "messaging_product" => "whatsapp",
            "to" => $formattedPhone,
            "type" => "template",
            "template" => [
                "name" => $template,
                "language" => ["code" => "es_EC"],
                "components" => [
                    [
                        "type" => "body",
                        "parameters" => array_map(fn($param) => ["type" => "text", "text" => $param], $parameters)
                    ]
                ]
            ]
        ];
        try {
            Log::info('Sending WhatsApp message', [
                'url' => $url,
                'body' => $body,
            ]);
            $response = $this->client->post($url, [
                'headers' => [
                    'Authorization' => "Bearer {$this->accessToken}",
                    'Content-Type' => 'application/json',
                ],
                'json' => $body,
            ]);

            $responseBody = $response->getBody()->getContents();
            $responseData = json_decode($responseBody, true);

            Log::info('WhatsApp message sent successfully', [
                'response' => $responseData,
                'status_code' => $response->getStatusCode()
            ]);

            return $responseData;
           
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $errorResponse = $e->getResponse();
            $errorBody = $errorResponse->getBody()->getContents();
            $errorData = json_decode($errorBody, true);
            
            Log::error('WhatsApp API Client Error', [
                'status_code' => $errorResponse->getStatusCode(),
                'error' => $errorData,
                'template' => $template,
                'to' => $to
            ]);
            
            return false;
        } catch (\Throwable $th) {
            Log::error('Error sending WhatsApp message', [
                'error' => $th->getMessage(),
                'template' => $template,
                'to' => $to
            ]);
            
            return false;
        }
    }

    /**
     * Verificar si una plantilla está aprobada
     */
    public function checkTemplateStatus($templateName)
    {
        try {
            // Obtener el Business Account ID primero
            $businessAccountUrl = "https://graph.facebook.com/v22.0/me/businesses";
            $businessResponse = $this->client->get($businessAccountUrl, [
                'headers' => [
                    'Authorization' => "Bearer {$this->accessToken}",
                ],
            ]);
            
            $businessData = json_decode($businessResponse->getBody()->getContents(), true);
            
            if (!isset($businessData['data']) || empty($businessData['data'])) {
                return 'no_business_account';
            }
            
            $businessId = $businessData['data'][0]['id'];
            
            // Obtener las plantillas del business account
            $url = "https://graph.facebook.com/v22.0/{$businessId}/message_templates";
            
            $response = $this->client->get($url, [
                'headers' => [
                    'Authorization' => "Bearer {$this->accessToken}",
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);
            
            if (isset($responseData['data'])) {
                foreach ($responseData['data'] as $template) {
                    if ($template['name'] === $templateName) {
                        return $template['status'];
                    }
                }
            }
            
            return 'not_found';
        } catch (\Throwable $th) {
            Log::error('Error checking template status', [
                'error' => $th->getMessage(),
                'template' => $templateName
            ]);
            return 'error';
        }
    }
}