<?php

namespace App\Console\Commands;

use App\Services\WhatsappService;
use Illuminate\Console\Command;

class TestWhatsappMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:test {phone : Phone number to test} {template? : Template name to use}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test WhatsApp message sending';

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
        $phone = $this->argument('phone');
        $template = $this->argument('template') ?? 'appointment_confirmation';
        
        $this->info("🧪 Testing WhatsApp message to: {$phone}");
        $this->info("📝 Using template: {$template}");
        
        // Parámetros de prueba
        $parameters = [
            'Juan Pérez',
            '2024-01-15 10:00',
            '2024-01-15 10:00'
        ];
        
        $this->info("📋 Parameters: " . json_encode($parameters));
        
        $result = $this->whatsappService->sendMessage($phone, $template, $parameters);
        
        if ($result) {
            $this->info("✅ Message sent successfully!");
            $this->info("📊 Response: " . json_encode($result, JSON_PRETTY_PRINT));
        } else {
            $this->error("❌ Failed to send message");
        }
        
        return 0;
    }
}
