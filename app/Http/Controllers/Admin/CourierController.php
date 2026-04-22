<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    public function index()
    {
        $couriers = Courier::all();
        return view('admin.couriers.index', compact('couriers'));
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
