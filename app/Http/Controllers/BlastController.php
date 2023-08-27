<?php

namespace App\Http\Controllers;
use App\Models\Campaign;


class BlastController extends Controller
{
    public function index(Campaign $campaign)
    {
       $blasts = $campaign->blasts()->orderBy('updated_at', 'desc')->paginate(20);
       $campaign_name = $campaign->name;
        return view('pages.campaign.datablasts', compact('blasts', 'campaign_name'));
    }
}
