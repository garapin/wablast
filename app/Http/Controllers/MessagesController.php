<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Models\MessageHistory;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MessagesController extends Controller
{
    protected $wa;
    public function __construct(WhatsappService $wa)
    {
        $this->wa = $wa;
    }

    public function index(Request $request)
    {
        $devices = $request
            ->user()
            ->devices()
            ->latest()
            ->paginate(10);
        return view('pages.messagetest', compact('devices'));
    }

    public function store(SendMessageRequest $request)
    {
      
        $receivers = explode('|', $request->number);
        $unique = array_unique($receivers);

        $success = 0;
        foreach ($unique as $number) {
            try {
                if ($request->type == 'text') {
                    $send = $this->wa->sendText($request, $number);
                } else if ($request->type == 'media') {
                    $send = $this->wa->sendMedia($request,$number);
                } else if ($request->type == 'button') {
                    $send = $this->wa->sendButton($request,$number);
                } else if ($request->type == 'template') {
                    $send = $this->wa->sendTemplate($request,$number);
                } else if ($request->type == 'list') {
                     $send = $this->wa->sendList($request,$number);
                   
                } else if ($request->type == 'poll'){
                    $send = $this->wa->sendPoll($request,$number);
                    
                }
                self::inputDate($request,$number,$send);
               $success = $send->status ? $success + 1 : $success;
            } catch (\Exception $e) {
                return redirect()->back()->with('alert', ['type' => 'danger','msg' => 'Error sending message to ' . $number .  ': ' .$e->getMessage()]);
                Log::error('Error sending message to ' . $number .  ': ' .$e->getMessage() );
            }
        }
        if ($success > 0) {
            return redirect()->back()->with('alert', ['type' => 'success','msg' => 'Success send message to ' . $success . ' number(s)',]);
        } else {
            return redirect()->back()->with('alert', ['type' => 'danger','msg' => 'Something went wrong,make sure your device is connected and the receiver number is valid',]);
        }
    }

    private static function inputDate($request,$number,$result) : void
    {
       try {
         $device = $request->user()->devices()->whereBody($request->sender)->first();
         // exclude _token and _method from $request->all()
         $data = array_diff_key($request->all(), array_flip(['_token', '_method']));
         MessageHistory::create([
            'user_id' => $request->user()->id,
            'device_id' => $device->id,
            'number' => $number,
            'message' => $request->message ? $request->message :($request->caption ? $request->caption : ''),
            'payload' => json_encode($data),
            'status' => $result->status ? 'success' : 'failed',
            'type' => $request->type,
            'send_by' => 'web',
         ]);
         $device->update([
            'message_sent' => $device->message_sent + 1,
         ]);
       } catch (\Throwable $th) {
        // Log::error('Error input data to database: ' . $th->getMessage());
       }
    }

 
}
