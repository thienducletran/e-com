<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    public function index()
    {
        $store = Auth::user()->store;
        return view('manager.store.index', compact('store'));
    }

    public function update(Request $request)
    {
        $store = Auth::user()->store;

        if (!$store) {
            return redirect()->back()->with('error', 'Không tìm thấy gian hàng.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        if ($request->name !== $store->name) {
            $data['slug'] = Str::slug($request->name);
        }

        if ($request->hasFile('logo')) {
            if ($store->logo && Storage::disk('public')->exists($store->logo)) {
                Storage::disk('public')->delete($store->logo);
            }
            $data['logo'] = $request->file('logo')->store('stores', 'public');
        }

        $store->update($data);

        return redirect()->back()->with('success', 'Cập nhật thông tin gian hàng thành công!');
    }
}
