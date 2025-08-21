<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    // 🏠 عرض كل العقارات
    public function index()
    {
        $properties = Property::with('images')->get(['id', 'title', 'price', 'city']);
        return response()->json($properties);
    }

    // ➕ إضافة عقار جديد + صور
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric',
            'type'        => 'required|string',
            'city'        => 'required|string',
            'address'     => 'required|string',
            'images.*'    => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // إنشاء العقار
        $property = Property::create([
            'title'       => $request->title,
            'description' => $request->description,
            'price'       => $request->price,
            'type'        => $request->type,
            'city'        => $request->city,
            'address'     => $request->address,
            'user_id'     => Auth::id(),
        ]);

        // رفع الصور وحفظها
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('properties', 'public');
                $property->images()->create([
                    'image_url' => $path
                ]);
            }
        }

        return response()->json($property->load('images', 'user'));
    }

    // 🔍 عرض عقار واحد
    public function show($id)
    {
        $property = Property::with('images', 'user')->findOrFail($id);
        return response()->json($property);
    }

    // ✏️ تحديث العقار (اختياري)
    public function update(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        // تأكد إن اللي بيعدل هو صاحب العقار
        if ($property->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $property->update($request->only([
            'title', 'description', 'price', 'type', 'city', 'address', 'status'
        ]));

        return response()->json($property->load('images'));
    }

    // ❌ حذف العقار (اختياري)
    public function destroy($id)
    {
        $property = Property::findOrFail($id);

        if ($property->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $property->delete();

        return response()->json(['message' => 'Property deleted successfully']);
    }
}
