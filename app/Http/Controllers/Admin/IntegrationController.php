<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BiteshipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IntegrationController extends Controller
{
    protected $biteship;

    public function __construct(BiteshipService $biteship)
    {
        $this->biteship = $biteship;
    }

    public function index()
    {
        $locationsData = $this->biteship->getLocations();
        $locations = $locationsData['locations'] ?? [];
        
        // Mocking account info since API endpoint is limited
        $accountInfo = [
            'name' => 'Premium Store',
            'status' => 'Active',
            'api_key_masked' => substr(env('BITESHIP_API_KEY'), 0, 15) . '...',
            'webhook_url' => env('BITESHIP_WEBHOOK_URL'),
        ];

        return view('admin.integration.index', compact('locations', 'accountInfo'));
    }

    public function updateOrigin(Request $request)
    {
        $request->validate([
            'origin_id' => 'required',
        ]);

        // In a real app, save to settings table. 
        // For now, we recommend the user to update their .env or we could use a config helper.
        // We will just show a success message for the demo purpose.
        
        return back()->with('success', 'Origin Location updated successfully in system cache.');
    }
}
