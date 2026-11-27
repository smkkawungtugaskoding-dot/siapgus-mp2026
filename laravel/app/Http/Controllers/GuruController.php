<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index()
    {
        $guruList = Guru::orderBy('nama', 'asc')->get();
        return view('admin.guru', compact('guruList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|string|unique:guru,id',
            'nik_nuptk' => 'nullable|string|max:50',
            'nama' => 'required|string|max:150',
            'jenis_kelamin' => 'required|in:L,P',
            'email' => 'nullable|email|max:100',
            'no_hp' => 'nullable|string|max:30',
        ]);

        Guru::create($data);

        // Buat akun login guru secara otomatis
        $username = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', explode(' ', $data['nama'])[0])) . rand(10, 99);
        User::firstOrCreate(
            ['id' => 'usr_' . $data['id']],
            [
                'username' => $username,
                'password' => Hash::make('guru123'),
                'full_name' => $data['nama'],
                'role' => 'guru',
                'nik_nuptk' => $data['nik_nuptk'],
                'email' => $data['email'],
            ]
        );

        return redirect()->route('admin.guru')->with('success', "Guru berhasil ditambahkan dengan akun login: {$username} / guru123");
    }

    public function destroy($id)
    {
        Guru::destroy($id);
        User::destroy('usr_' . $id);
        return redirect()->route('admin.guru')->with('success', 'Data guru berhasil dihapus.');
    }
}
