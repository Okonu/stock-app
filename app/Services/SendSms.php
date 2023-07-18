<?php

namespace App\Services;

class SendSms
{
    protected $userId = 'brance';
    protected $passkey = 'ad1191e66088a92904e57b7679a1078c959527a2';
    protected $senderId = 'HPKSMS';
    protected $msgType = 'text';
    protected $duplicatecheck = 'true';
    protected $output = 'json';
    protected $sendMethod = 'quick';

    public function sendSms($contacts, $message, $no_contacts = "")
    {
        $url = "https://smsportal.hostpinnacle.co.ke/SMSApi/send";
        $payload = [
            "userid" => $this->userId,
            "password" => $this->passkey,
            "mobile" => $contacts,
            "msg" => $message,
            "senderid" => $this->senderId,
            "msgType" => $this->msgType,
            "duplicatecheck" => $this->duplicatecheck,
            "output" => $this->output,
            "sendMethod" => $this->sendMethod
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "apikey: somerandomuniquekey",
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded"
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200) {
            if (!$no_contacts) {

               // echo $response;
            }
            return true;
        } else {
            ///echo "Request failed with status code: " . $httpCode;
            return false;
        }
    }

    public function recordSmsSent($contacts, $message, $groupid, $no_contacts)
    {
        // 
    }
}
