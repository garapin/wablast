<?php 

namespace App\Services\Impl;

use App\Services\WhatsappService;
use Illuminate\Support\Facades\Http;

class WhatsappServiceImpl implements WhatsappService {

  private $url;
  

  public function __construct()
  {
    $this->url = env('WA_URL_SERVER');
  }
  public function fetchGroups($device): object
  {
        $fetch = Http::withOptions(['verify' => false])->asForm()->post($this->url .'/backend-getgroups',['token' => $device->body]);
        return json_decode($fetch->body());

  }

  public function startBlast($data): object
  {
    $res = Http::withOptions(['verify' => false])
    ->asForm()
    ->post(
        $this->url . '/backend-blast',
        [
            'data' => json_encode($data),
            'delay' => 1,
        ]
    );
    return json_decode($res->body());
  }

  public function sendText($request,$receiver): object | bool
  {
     $results = Http::withOptions(['verify' => false])->asForm()->post($this->url . '/backend-send-text', [
          'token' => $request->sender,
          'number' =>$receiver,
          'text' => $request->message,
    ]);

    return json_decode($results->body());

  }


  public function sendMedia($request,$receiver): object | bool
  {
    // GET FILE NAME from $request->url
    $fileName = explode('/', $request->url);
    $fileName = explode('.', end($fileName));
    $fileName = $fileName[0];
 
     $data = [
            'token' => $request->sender,
            'url' => $request->url,
            'number' => $receiver,
            'caption' => $request->caption ?? '',
            'filename' => $fileName,
            'type' => $request->media_type,
            'ptt' => $request->ptt ? ($request->ptt == 'vn' ? true : false) : false,
        ];
    $results = Http::withOptions(['verify' => false])->asForm()->post($this->url . '/backend-send-media', $data);


    return json_decode($results->body());
  }


  public function sendButton($request,$receiver): object | bool
  {
       $buttons = [];
       foreach ($request->button as $button) {
            $buttons[] = ['displayText' => $button, ];
        }
        // check url if exists,set to image if not exists cheeck thumbnail if exists set to image
        $image = $request->url ? $request->url : ($request->image ? $request->image : '');
        $data = [
            'token' => $request->sender,
            'number' => $receiver,
            'button' => json_encode($buttons),
            'message' => $request->message,
            'footer' => $request->footer ?? '',
            'image' => $image,
        ];
;
        $results =  Http::withOptions(['verify' => false])->asForm()->post($this->url . '/backend-send-button', $data);
        return json_decode($results->body());

  }

  public function sendTemplate($request,$receiver): object | bool
  {
         $templates = [];

         $ii = 1;
          foreach ($request->template as $template) {
            $ii++;
            $typedest = explode('|', $template)[0] == 'url' ? 'url' : (explode('|', $template)[0] == 'call' ? 'phoneNumber' : 'id');
            $type = explode('|', $template)[0] == 'id' ? 'quickReplyButton' : explode('|', $template)[0] . 'Button';
            $templates[] = [
                'index' => $ii,
                $type => [
                    'displayText' => explode('|', $template)[1],
                    $typedest => explode('|', $template)[2],
                ],
            ];
        
            }
             $image = $request->url ? $request->url : ($request->image ? $request->image : '');
            $data = [
            'token' => $request->sender,
            'number' => $receiver,
            'button' => json_encode($templates),
            'text' => $request->message,
            'footer' => $request->footer ?? '',
            'image' => $image,
            
        ];

        $results =  Http::withOptions(['verify' => false])->asForm()->post($this->url . '/backend-send-template', $data);
        return json_decode($results->body());
  }


  public function sendList($request,$receiver) : Object | bool
  {
     $section['title'] = $request->title;
      $i = 1;
      foreach ($request->list as $menu) {
            $i++;
            $section['rows'][] = [
                'title' => $menu,
                'rowId' => 'id' . $i,
                'description' => '',
            ];
        }
       $data = [
            'token' => $request->sender,
            'number' => $receiver,
            'list' => json_encode($section),
            'text' => $request->message,
            'footer' => $request->footer ?? '',
            'title' => $request->title,
            'buttonText' => $request->buttontext,
        ];


        $results =  Http::withOptions(['verify' => false])->asForm()->post($this->url . '/backend-send-list', $data);
        return json_decode($results->body());

  }

  public function sendPoll ($request ,$receiver) : Object | bool
  {
    $optionss = [];
    foreach ($request->option as $opt) {
       $optionss[] = $opt;
  }
 
 
    $data = [
      "token" => $request->sender,
      "number" => $receiver,
      "name" => $request->name,
      "options" => json_encode($optionss),
      "countable" => $request->countable === "1" ? true : false,
    ];

    $results = Http::withOptions(['verify' => false])->asForm()->post($this->url . '/backend-send-poll', $data);
    return json_decode($results->body());

  }
}
?>