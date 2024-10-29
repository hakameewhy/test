<?php

namespace App\Http\Controllers;

use App\Models\Konsumen;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KonsumenController extends Controller
{
    public function index(): View
    {
        $konsumens = Konsumen::latest()->paginate(10);
        return view('konsumens.index', compact('konsumens'));
    }

    public function create(): View
    {
        return view('konsumens.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'profilepic' => 'required|image|mimes:jpeg,jpg,png,gif|max:2048',
            'Name' => 'required|min:5',
            'birthday' => 'required|date',
            'address' => 'required|min:10',
            'gender' => 'required|in:male,female',
        ]);

        $profilepic = $request->file('profilepic');
        $profilepicName = $profilepic->hashName();
        $profilepic->storeAs('konsumens', $profilepicName, 'public');

        Konsumen::create([
            'profilepic' => $profilepicName,
            'Name' => $request->Name,
            'birthday' => $request->birthday,
            'address' => $request->address,
            'gender' => $request->gender,
        ]);

        return redirect()->route('konsumens.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function show(string $id): View
    {
        $konsumen = Konsumen::findOrFail($id);
        return view('konsumens.show', compact('konsumen'));
    }

    public function edit(string $id): View
    {
        $konsumen = Konsumen::findOrFail($id);
        return view('konsumens.edit', compact('konsumen'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'profilepic' => 'image|mimes:jpeg,jpg,png|max:2048',
            'Name' => 'required|min:5',
            'birthday' => 'required|date',
            'address' => 'required|min:10',
            'gender' => 'required|in:Male,Female',
        ]);

        $konsumen = Konsumen::findOrFail($id);

        // Logging untuk debugging
        \Log::info('Data gender:', ['gender' => $request->gender]);

        if ($request->hasFile('profilepic')) {
            $profilepic = $request->file('profilepic');
            $profilepicName = $profilepic->hashName();
            $profilepic->storeAs('konsumens', $profilepicName, 'public');

            // Delete old profile picture
            Storage::delete('public/konsumens/' . $konsumen->profilepic);

            // Update konsumen with new profile picture and other data
            $konsumen->update([
                'profilepic' => $profilepicName,
                'Name' => $request->Name,
                'birthday' => $request->birthday,
                'address' => $request->address,
                'gender' => $request->gender,
            ]);
        } else {
            // Update without new profile picture
            $konsumen->update([
                'Name' => $request->Name,
                'birthday' => $request->birthday,
                'address' => $request->address,
                'gender' => $request->gender,
            ]);
        }

        return redirect()->route('konsumens.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function destroy($id): RedirectResponse
    {
        $konsumen = Konsumen::findOrFail($id);

        Storage::delete('public/konsumens/' . $konsumen->profilepic);

        $konsumen->delete();

        return redirect()->route('konsumens.index')->with(['success' => 'Data berhasil dihapus']);
    }
}