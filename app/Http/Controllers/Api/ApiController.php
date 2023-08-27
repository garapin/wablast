<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendMessageRequest;
use App\Models\Device;
use App\Models\MessageHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    protected $wa;
    public function __construct(WhatsappService $wa)
    {
        $this->wa = $wa;
    }

    private static function validateparams($request) {
          $rules = (new SendMessageRequest())->rules();

        $validated = validator($request->all(), $rules);

        if ($validated->fails()) {
            return response()->json(['status' => false, 'msg' => 'Invalid data!', 'errors' => $validated->errors(),],Response::HTTP_BAD_REQUEST);
        }
        return true;
    }

    public function messageText(Request $request)
    {
        $request->merge(['type' => 'text']);
       if (self::validateparams($request) !== true) {
            return self::validateparams($request);
       }
        $receivers = explode('|', $request->number);
        $receivers = array_unique($receivers);
        $success = 0;
        try {
            foreach ($receivers as $number) {
                $sendMessage = $this->wa->sendText($request, $number);
                 self::inputDate($request,$number,$sendMessage);
                $success = $sendMessage->status ? $success + 1 : $success;
            }
            return $this->handleResponse($success);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'msg' => 'Failed to send message!','errors' => 'Failed to access whatsapp server',], Response::HTTP_BAD_REQUEST);
        }
    }

    public function messageMedia(Request $request)
    {
       $request->merge(['type' => 'media']);
         if (self::validateparams($request) !== true) {
                return self::validateparams($request);
         }
        $receivers = explode('|', $request->number);
        $receivers = array_unique($receivers);
        $success = 0;
        try {
            foreach ($receivers as $number) {
                $sendMessage = $this->wa->sendMedia($request, $number);
                 self::inputDate($request,$number,$sendMessage);
                $success = $sendMessage->status ? $success + 1 : $success;
            }
            return $this->handleResponse($success);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'msg' => 'Failed to send message!','errors' => 'Failed to access whatsapp server',], Response::HTTP_BAD_REQUEST);
        }
    }



    public function messageButton(Request $request)
    {
        $request->merge(['type' => 'button']);
        // if the method is GET, change $request->button , to params button explode by comma
        if ($request->isMethod('get')) {
            $request->merge(['button' => explode(',', $request->button)]);
        }
       
        if (self::validateparams($request) !== true) {
            return self::validateparams($request);
        }
        $receivers = explode('|', $request->number);
        $receivers = array_unique($receivers);
        $success = 0;
        try {
            foreach ($receivers as $number) {
                $sendMessage = $this->wa->sendButton($request, $number);
                $success = $sendMessage->status ? $success + 1 : $success;
            }
            return $this->handleResponse($success);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json(['status' => false,'msg' => 'Failed to send message!','errors' => 'Failed to access whatsapp server',], Response::HTTP_BAD_REQUEST);
        }
    }

       public function messageTemplate(Request $request)
    {
        $request->merge(['type' => 'template']);
        // if the method is GET, change $request->button , to params button explode by comma
        if ($request->isMethod('get')) {
            $request->merge(['template' => explode(',', $request->template)]);
        }
        if (self::validateparams($request) !== true) {
            return self::validateparams($request);
        }
        $receivers = explode('|', $request->number);
        $receivers = array_unique($receivers);
        $success = 0;
        try {
            foreach ($receivers as $number) {
                $sendMessage = $this->wa->sendTemplate($request, $number);
                 self::inputDate($request,$number,$sendMessage);
                $success = $sendMessage->status ? $success + 1 : $success;
            }
            return $this->handleResponse($success);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'msg' => 'Failed to send message!','errors' => 'Failed to access whatsapp server',], Response::HTTP_BAD_REQUEST);
        }
    }

     public function messageList(Request $request)
    {
        $request->merge(['type' => 'list']);
        // if the method is GET, change $request->button , to params button explode by comma
        if ($request->isMethod('get')) {
            $request->merge(['list' => explode(',', $request->list)]);
        }
        if (self::validateparams($request) !== true) {
            return self::validateparams($request);
        }
        $receivers = explode('|', $request->number);
        $receivers = array_unique($receivers);
        $success = 0;
        try {
            foreach ($receivers as $number) {
                $sendMessage = $this->wa->sendList($request, $number);
                 self::inputDate($request,$number,$sendMessage);
                $success = $sendMessage->status ? $success + 1 : $success;
            }
            return $this->handleResponse($success);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'msg' => 'Failed to send message!','errors' => 'Failed to access whatsapp server',], Response::HTTP_BAD_REQUEST);
        }
    }
     public function messagePoll(Request $request)
    {
        $request->merge(['type' => 'poll']);
        // if the method is GET, change $request->button , to params button explode by comma
        if ($request->isMethod('get')) {
            $request->merge(['option' => explode(',', $request->option)]);
        }
        
        if (self::validateparams($request) !== true) {
            return self::validateparams($request);
        }
        $receivers = explode('|', $request->number);
        $receivers = array_unique($receivers);
        $success = 0;
        try {
            foreach ($receivers as $number) {
                $sendMessage = $this->wa->sendPoll($request, $number);
                 self::inputDate($request,$number,$sendMessage);
                $success = $sendMessage->status ? $success + 1 : $success;
            }
            return $this->handleResponse($success);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'msg' => 'Failed to send message!','errors' => 'Failed to access whatsapp server',], Response::HTTP_BAD_REQUEST);
        }
    }

 

    private function handleResponse($success)
    {
        if ($success > 0) {
            return response()->json( ['status' => true,'msg' => 'Message sent successfully!'],Response::HTTP_OK);
        } else {
            return response()->json(['status' => false,'msg' => 'Failed to send message!'],Response::HTTP_BAD_REQUEST );
        }
    }


 
     private static function inputDate($request,$number,$result) : void
    {
       try {
         $device = Device::whereBody($request->sender)->first();
         // exclude _token and _method from $request->all()
         $data = array_diff_key($request->all(), array_flip(['_token', '_method']));
         MessageHistory::create([
            'user_id' => $device->user_id,
            'device_id' => $device->id,
            'number' => $number,
            'message' => $request->message ? $request->message :($request->caption ? $request->caption : ''),
            'payload' => json_encode($data),
            'status' => $result->status ? 'success' : 'failed',
            'type' => $request->type,
            'send_by' => 'api',
         ]);
         $device->update([
            'message_sent' => $device->message_sent + 1,
         ]);
       } catch (\Throwable $th) {
         Log::error('Error input data to database: ' . $th->getMessage());
       }

    }
   




    public function generateQr(Request $request)
    {

        $validated = validator($request->all(), [
            'api_key' => 'required|exists:users,api_key',
            'device' => 'required|exists:devices,body',
        ]);

        if ($validated->fails()) {
            return response()->json(['status' => false, 'msg' => 'Invalid data!', 'errors' => $validated->errors(),],Response::HTTP_BAD_REQUEST);
        }

        $user = User::whereApiKey($request->api_key)->first();
        if($user->api_key != $request->api_key){
            return response()->json(['status' => false,'msg' => 'Wrong api key!'],Response::HTTP_BAD_REQUEST);
        }
        if ($user->is_expired_subscription) {
            return response()->json( ['status' => false,'msg' => 'Your subscription has expired!',], Response::HTTP_BAD_REQUEST);
        }
        
        if (!$request->has('device') || !$request->has('api_key')) {
            return response()->json(['status' => false,'msg' => 'Wrong parameters!', ],Response::HTTP_BAD_REQUEST);
        } 

        $device = Device::whereBody($request->device)->first();
        if ($device->status == 'Connected') {
            return response()->json(['status' => false,'msg' => 'Device already connected!', ],Response::HTTP_BAD_REQUEST);
        }
     
        try {
            $post = Http::withOptions(['verify' => false])->asForm()->post(env('WA_URL_SERVER') . '/backend-generate-qr', ['token' => $request->device,]);
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => false,
                    'msg' => 'Failed to access the whatsapp server!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
        // send respon json from post
        return response()->json(json_decode($post->body()), Response::HTTP_OK);
    }
}
