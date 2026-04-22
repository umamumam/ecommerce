<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    public function index()
    {
        $couriers = \App\Models\Courier::all();
        $originId = \App\Models\Setting::where('key', 'biteship_origin_id')->first()?->value;
        $originLabel = \App\Models\Setting::where('key', 'biteship_origin_label')->first()?->value;
        
        return view('admin.couriers.index', compact('couriers', 'originId', 'originLabel'));
    }

    public function updateBiteshipSettings(Request $request)
    {
        $request->validate([
            'origin_area_id' => 'required',
            'origin_label' => 'required',
        ]);

        \App\Models\Setting::updateOrCreate(
            ['key' => 'biteship_origin_id'],
            ['value' => $request->origin_area_id]
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'biteship_origin_label'],
            ['value' => $request->origin_label]
        );

        return response()->json(['success' => true, 'message' => 'Lokasi penjemputan berhasil diperbarui']);
    }

    public function toggle(Request $request, $id)
    {
        $courier = Courier::findOrFail($id);
        $courier->is_active = !$courier->is_active;
        $courier->save();

        return response()->json([
            'success' => true,
            'message' => "Status {$courier->name} berhasil diubah",
            'is_active' => $courier->is_active
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:couriers,code',
            'name' => 'required',
        ]);

        Courier::create([
            'code' => strtolower($request->code),
            'name' => $request->name,
            'is_active' => true
        ]);

        return back()->with('success', 'Kurir baru berhasil ditambahkan');
    }
}
