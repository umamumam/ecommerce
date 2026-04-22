<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\XenditService;
use App\Models\WebhookLog;
use Illuminate\Http\Request;

class XenditController extends Controller
{
    protected $xendit;

    public function __construct(XenditService $xendit)
    {
        $this->xendit = $xendit;
    }

    public function index()
    {
        $balanceData = $this->xendit->getBalance();
        $balance = $balanceData['balance'] ?? 0;
        
        $logs = WebhookLog::where('provider', 'xendit')->latest()->take(10)->get();
        
        $config = [
            'secret_key' => substr(env('XENDIT_SECRET_KEY'), 0, 15) . '...',
            'webhook_url' => url('/webhook/xendit'),
        ];

        return view('admin.xendit.index', compact('balance', 'logs', 'config'));
    }
}
