<?php

namespace App\Http\Controllers;

use Bashy\CampaignMonitor\Facades\CampaignMonitor;
use Illuminate\Routing\Controller as BaseController;

class MembersController extends BaseController
{
    public function index(){
        return ['subscribers' => CampaignMonitor::lists('475bd5a95d1c5d7110eecf7ee6351fe2')->get_active_subscribers()->response];
    }

    public function store(){
        return ['result' => CampaignMonitor::subscribers('475bd5a95d1c5d7110eecf7ee6351fe2')->add([
            'EmailAddress' => request('EmailAddress'),
            'Name' => request('Name'),
            'ConsentToTrack' => 'Yes',
            "Resubscribe"=> true,
        ])];
    }

    public function delete(){
        return ['result' => CampaignMonitor::subscribers('475bd5a95d1c5d7110eecf7ee6351fe2')->unsubscribe(request('EmailAddress'))];
    }
}
