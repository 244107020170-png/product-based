<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OwnerFieldController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'type'        => 'required|string|max:50',
            'price'       => 'required|numeric|min:0',
            'location'    => 'required|string|max:255',
            'maps_link'   => 'nullable|url|max:500',
            'open_time'   => 'required|string|max:5',
            'close_time'  => 'required|string|max:5',
            'facilities'  => 'nullable|array',
            'image'       => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('fields', 'public');
        }

        Field::create([
            'name'           => $data['name'],
            'type'           => $data['type'],
            'description'    => $request->input('description'),
            'location'       => $data['location'],
            'maps_link'      => $data['maps_link'] ?? null,
            'price_per_hour' => $data['price'],
            'open_time'      => $data['open_time'],
            'close_time'     => $data['close_time'],
            'owner_id'       => auth()->id(),
            'image'          => $path,
            'facilities'     => $data['facilities'] ?? [],
            'is_available'   => true,
        ]);

        return redirect()->route('owner.kelolaLapangan')
            ->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function update(Request $request, Field $field)
    {
        if ($field->owner_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'type'        => 'required|string|max:50',
            'price'       => 'required|numeric|min:0',
            'location'    => 'required|string|max:255',
            'maps_link'   => 'nullable|url|max:500',
            'open_time'   => 'required|string|max:5',
            'close_time'  => 'required|string|max:5',
            'facilities'  => 'nullable|array',
            'image'       => 'nullable|image|max:2048',
        ]);

        $updateData = [
            'name'           => $data['name'],
            'type'           => $data['type'],
            'location'       => $data['location'],
            'maps_link'      => $data['maps_link'] ?? null,
            'price_per_hour' => $data['price'],
            'open_time'      => $data['open_time'],
            'close_time'     => $data['close_time'],
            'facilities'     => $data['facilities'] ?? [],
        ];

        if ($request->hasFile('image')) {
            if ($field->image) {
                Storage::disk('public')->delete($field->image);
            }
            $updateData['image'] = $request->file('image')->store('fields', 'public');
        }

        $field->update($updateData);

        return redirect()->route('owner.kelolaLapangan')
            ->with('success', 'Lapangan berhasil diperbarui.');
    }

    public function destroy(Field $field)
    {
        if ($field->owner_id !== auth()->id()) {
            abort(403);
        }

        if ($field->image) {
            Storage::disk('public')->delete($field->image);
        }

        $field->delete();

        return redirect()->route('owner.kelolaLapangan')
            ->with('success', 'Lapangan berhasil dihapus.');
    }

    public function edit(Field $field)
    {
        if ($field->owner_id !== auth()->id()) {
            abort(403);
        }

        return view('owner.tambahLapangan', compact('field'));
    }
}
