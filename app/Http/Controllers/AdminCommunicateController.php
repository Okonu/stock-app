<?php

namespace App\Http\Controllers;

use App\Models\Seeker;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminCommunicateController extends Controller
{
    protected $userId = 'brance';
    protected $passkey = 'ad1191e66088a92904e57b7679a1078c959527a2';
    protected $senderId = 'HPKSMS';
    protected $msgType = 'text';
    protected $duplicatecheck = 'true';
    protected $output = 'json';
    protected $sendMethod = 'quick';

    public function index()
    {
        $companyTelephones = Company::pluck('telephone')->all();
        $seekerPhones = Seeker::pluck('phone')->all();

        return view('admin.communicate.message', compact('companyTelephones', 'seekerPhones'));
    }

    public function sendSms(Request $request)
    {
        $group = $request->input('group');
        $message = $request->input('message');

        if ($group === 'all') {
            $companyTelephones = Company::pluck('telephone')->all();
            $seekerPhones = Seeker::pluck('phone')->all();
            $contacts = array_merge($companyTelephones, $seekerPhones);
        } elseif ($group === 'company') {
            $contacts = Company::pluck('telephone')->all();
        } elseif ($group === 'seeker') {
            $contacts = Seeker::pluck('phone')->all();
        } else {
            // Invalid group value
            return back()->with('error', 'Invalid group selected');
        }

        // dd($contacts, $message);
        
        //-> send to each contacts independently
        
        $response = null;
        
        foreach($contacts as $contact){

            $response = $this->sendSmsToContacts($contact, $message);
        }

        if ($response === true) {
            return back()->with('success', 'Message sent successfully');
        } else {
            return back()->with('error', 'Failed to send message');
        }
    }

    protected function sendSmsToContacts($contacts, $message)
    {
         $url = "https://smsportal.hostpinnacle.co.ke/SMSApi/send";
        $payload = [
            "userid" => $this->userId,
            "password" => $this->passkey,
            "mobile" =>$contacts,//implode(',', $contacts),
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

        // print_r($response);
        // exit();

       if ($httpCode == 200) {
            
                // $this->recordSmsSent($contacts, $message, $groupid, $no_contacts);
                echo $response;
           
            return true;
        } else {
            echo "Request failed with status code: " . $httpCode;
            return false;
        }
    }

    protected function recordSmsSent($contacts, $message, $groupid, $no_contacts)
    {
        // Implement recording of sent SMS to the database
    }
}
