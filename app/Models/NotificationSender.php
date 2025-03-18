<?php

    namespace App\Models;
    
    use CodeIgniter\Model;
    
    class NotificationSender extends Model
    {
    
        private $apiUrl = 'http://api.textmebot.com/send.php';
        private $apiKey = 'RZ3eEfCTk4FS';
        private $defaultCountryCode = '218';
    
        public function __construct()
        {
            
        }
    
        private function formatPhoneNumber($phoneNumber)
        {
            if(strpos($phoneNumber, '00') === 0) 
            {
                return '+' . substr($phoneNumber, 2);
            }
            if(substr($phoneNumber, 0, 1) === '+') 
            {
                return $phoneNumber;
            }
            if(substr($phoneNumber, 0, 2) === '09') 
            {
                return $this->defaultCountryCode . substr($phoneNumber, 1);
            }
            return $this->defaultCountryCode . $phoneNumber;
        }
    
        
        public function sendTextHandler(array $recipients, string $message)
        {
            $db = \Config\Database::connect();
            $builder = $db->table('pending_messages');
            foreach ($recipients as $recipient) 
            {
                $builder->insert([
                    'phone'     => $recipient,
                    'message'   => $message,
                    'status'    => 'pending',
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
            $cmd = 'nohup curl -s https://portal.i-volunteer.ly/SendGroup > /dev/null 2>&1 &';
            shell_exec($cmd);
            return true;
        }
    
        public function sendText(array $recipients, string $message)
        {
            $results = [];
            foreach ($recipients as $index => $recipient) 
            {
                $phone = $this->formatPhoneNumber($recipient);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $this->apiUrl . '?recipient=' . $phone . '&apikey=' . $this->apiKey . '&text=' . urlencode($message));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
                curl_setopt($ch, CURLOPT_HEADER, false);
                $response = curl_exec($ch);
                $error = curl_error($ch);
                curl_close($ch);
                $results[] = ['phone' => $phone,'success' => $response !== false,'error' => $error,];
            }
            return $results;
        }
        
        public function sendTexts($recipient, string $message)
        {
            $results = [];
            $phone = $this->formatPhoneNumber($recipient);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->apiUrl . '?recipient=' . $phone . '&apikey=' . $this->apiKey . '&text=' . urlencode($message));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $response = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);
            $results[] = ['phone' => $phone,'success' => $response !== false,'error' => $error,];
            return $results;
        }
    
        public function sendDocument(array $recipients, string $filePath, string $message = '')
        {
            $results = [];
            foreach ($recipients as $recipient) 
            {
                $phone = $this->formatPhoneNumber($recipient);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $this->apiUrl . '?recipient=' . $phone . '&apikey=' . $this->apiKey . '&text=' . urlencode($message));
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, ['file' => new CURLFile($filePath)]);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $response = curl_exec($ch);
                $error = curl_error($ch);
                curl_close($ch);
                $results[] = ['phone' => $phone,'success' => $response !== false,'error' => $error,];
            }
            return $results;
        }
    }
