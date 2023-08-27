<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAutoreplyRequest;
use Illuminate\Http\Request;
use App\Models\Autoreply;
use App\Services\MessageService;

class AutoreplyController extends Controller
{
    protected $msgservice;
    public function __construct(MessageService $msgservice)
    {
        $this->msgservice = $msgservice;
    }

    public function index(Request $request)
    {
        $autoreplies = $request->user() ->autoreplies()->whereHas('device', function ($q) {
                $q->where( 'id',session()->has('selectedDevice')? session()->get('selectedDevice')['device_id']  : '');
        });
        $autoreplies->when($request->keyword != null, function ($query) use ($request) {
            return $query->where('keyword', 'like', '%' . $request->keyword . '%');
        });
        $autoreplies = $autoreplies->latest()  ->paginate(15);
        return view('pages.autoreply', compact('autoreplies'));
    }

    public function store(StoreAutoreplyRequest $request)
    {
       
        $type = $request->type;
        try {
            //code...
            $reply = $this->msgservice->format($type, (object) $request->all());
        } catch (\Throwable $th) {
            return back()->with(['alert' => ['type' => 'danger', 'msg' => $th->getMessage()]]);
        }
        $data = $request->all();
        $data['reply'] = json_encode($reply);
        $data['device_id'] = $request->device;
        $request ->user() ->autoreplies()->create($data);
        return redirect(route('autoreply'))->with('alert', ['type' => 'success','msg' => 'Your auto reply was added!']);
    }

  



    public function destroy(Request $request)
    {
        Autoreply::whereId($request->id)->delete();
        return redirect(route('autoreply'))->with('alert', ['type' => 'success','msg' => 'Deleted', ]);
    }


    public function update (Request $request, Autoreply $autoreply){
       try {
        // if $request->keyword and exists in $autoreply with same device_id
        if ($request->keyword && $request->keyword != $autoreply->keyword) {
            $keyword = $request->keyword;
            $device_id = $autoreply->device_id;
            $check = Autoreply::where('keyword', $keyword)->where('device_id', $device_id)->first();
            if ($check) {
                return response()->json(['status' => 'error', 'msg' => 'Keyword already exists']);
            }
        }
        $autoreply->update([
            'keyword' => $request->keyword ?? $autoreply->keyword,
            'status' => $request->status ?? $autoreply->status,
            'is_quoted' => $request->quoted ?? $autoreply->is_quoted,
        ]);
        return response()->json(['error' => false, 'msg' => 'Updated']);
        
       } catch (\Throwable $th) {
        //throw $th;
       }
    }
}
