<?php

namespace App\Console\Commands;

use App\Models\Blast;
use App\Models\Campaign;
use App\Services\WhatsappService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class StartBlast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:blast';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $wa;
    public function __construct(WhatsappService $wa)
    {
        parent::__construct();
        $this->wa = $wa;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle()
    {
        $waitingCampaigns = Campaign::where('schedule', '<=', now())
            ->whereIn('status', ['waiting', 'processing'])
            ->with('phonebook', 'device')
            ->get();
     

        foreach ($waitingCampaigns as $campaign) {
            $countPhonebook = $campaign->phonebook->contacts()->count();
            if ($countPhonebook == 0) {
                $campaign->update(['status' => 'failed']);
                continue;
            }
      try {
            if ($campaign->device->status != 'Connected') {
                $campaign->update(['status' => 'paused']);
                continue;
            }

            $campaign->update(['status' => 'processing']);
            $pendingBlasts = $this->getPendingBlasts($campaign);
            if ($pendingBlasts->count() == 0) {
                continue;
            }
            // send progress

            $blastdata = [];

            foreach ($pendingBlasts as $blast) {
                $blastdata[] = [
                    'receiver' => $blast->receiver,
                    'message' => $blast->message,
                ];
            }
            $data = [
                'data' => $blastdata,
                'delay' => $campaign->delay > 20 ? 20 : $campaign->delay,
                'campaign_id' => $campaign->id,
                'sender' => $campaign->device->body,
            ];

         
                $results = $this->wa->startBlast($data);
                $campaign->update(['status' => 'processing']);
            } catch (\Throwable $th) {
                $campaign->blasts()->where('status','pending')->update(["status" => "failed"]);
                $campaign->update(['status' => 'failed']);
                Log::error($th);
            }
        }

        return 0;
    }

    public function getPendingBlasts($campaign)
    {
        $pendingBlasts = $campaign
            ->blasts()
            ->where('status', 'pending')
            ->limit(15)
            ->get();
        if ($pendingBlasts->count() == 0) {
            $campaign->update(['status' => 'completed']);
            return collect();
        }
        return $pendingBlasts;
    }
}
