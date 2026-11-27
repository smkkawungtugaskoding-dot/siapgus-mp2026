<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index()
    {
        $mapelList = MataPelajaran::orderBy('nama', 'asc')->get();
        return view('admin.mapel', compact('mapelList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|string|unique:mata_pelajaran,id',
            'kode' => 'required|string|max:30',
            'nama' => 'required|string|max:150',
            'kktp' => 'required|numeric|min:0|max:100',
            'kelompok' => 'nullable|string|max:50',
            'fase' => 'nullable|string|max:10',
            'jam_pekan' => 'nullable|numeric|min:1',
        ]);

        MataPelajaran::create($data);
        return redirect()->route('admin.mapel')->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        MataPelajaran::destroy($id);
        return redirect()->route('admin.mapel')->with('success', 'Mata Pelajaran berhasil dihapus.');
    }
}
