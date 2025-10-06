<?php

namespace App\Console\Commands;

use App\Services\WhatsappService;
use Illuminate\Console\Command;

class CheckWhatsappTemplates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:check-templates {template? : Template name to check}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check WhatsApp template status and configuration';

    protected $whatsappService;

    public function __construct(WhatsappService $whatsappService)
    {
        parent::__construct();
        $this->whatsappService = $whatsappService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Checking WhatsApp configuration...');
        
        // Verificar credenciales
        $accessToken = env('WHATSAPP_ACCESS_TOKEN');
        $phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');
        
        if (!$accessToken) {
            $this->error('❌ WHATSAPP_ACCESS_TOKEN not configured in .env');
            return 1;
        }
        
        if (!$phoneNumberId) {
            $this->error('❌ WHATSAPP_PHONE_NUMBER_ID not configured in .env');
            return 1;
        }
        
        $this->info('✅ Credentials configured');
        $this->info("📱 Phone Number ID: {$phoneNumberId}");
        
        // Verificar plantilla específica o todas
        $templateName = $this->argument('template');
        
        if ($templateName) {
            $this->checkSpecificTemplate($templateName);
        } else {
            $this->checkAllTemplates();
        }
        
        return 0;
    }
    
    private function checkSpecificTemplate($templateName)
    {
        $this->info("🔍 Checking template: {$templateName}");
        
        $status = $this->whatsappService->checkTemplateStatus($templateName);
        
        switch ($status) {
            case 'APPROVED':
                $this->info("✅ Template '{$templateName}' is APPROVED");
                break;
            case 'PENDING':
                $this->warn("⏳ Template '{$templateName}' is PENDING approval");
                break;
            case 'REJECTED':
                $this->error("❌ Template '{$templateName}' was REJECTED");
                break;
            case 'DISABLED':
                $this->error("🚫 Template '{$templateName}' is DISABLED");
                break;
            case 'not_found':
                $this->error("❌ Template '{$templateName}' not found");
                break;
            case 'error':
                $this->error("❌ Error checking template '{$templateName}'");
                break;
            default:
                $this->warn("⚠️ Unknown status for template '{$templateName}': {$status}");
        }
    }
    
    private function checkAllTemplates()
    {
        $this->info('🔍 Checking all available templates...');
        
        try {
            $client = new \GuzzleHttp\Client();
            // Usar el endpoint correcto para obtener las plantillas
            $url = "https://graph.facebook.com/v22.0/" . env('WHATSAPP_PHONE_NUMBER_ID') . "/message_templates";
            
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => "Bearer " . env('WHATSAPP_ACCESS_TOKEN'),
                ],
            ]);
            
            $data = json_decode($response->getBody()->getContents(), true);
            
            if (isset($data['data']) && count($data['data']) > 0) {
                $this->info('📋 Available templates:');
                
                $headers = ['Name', 'Status', 'Category', 'Language'];
                $rows = [];
                
                foreach ($data['data'] as $template) {
                    $rows[] = [
                        $template['name'],
                        $template['status'],
                        $template['category'] ?? 'N/A',
                        $template['language'] ?? 'N/A'
                    ];
                }
                
                $this->table($headers, $rows);
            } else {
                $this->warn('⚠️ No templates found');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Error fetching templates: ' . $e->getMessage());
            $this->info('💡 Note: Templates are managed in WhatsApp Business Manager');
            $this->info('🔗 Check: https://business.facebook.com/wa/manage/message-templates');
        }
    }
}
