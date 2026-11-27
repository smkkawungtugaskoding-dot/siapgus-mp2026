<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        $siswaList = Siswa::orderBy('kelas', 'asc')->orderBy('rombel', 'asc')->orderBy('nama', 'asc')->get();
        return view('admin.siswa', compact('siswaList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|string|unique:siswa,id',
            'nisn' => 'nullable|string|max:30',
            'nama' => 'required|string|max:150',
            'kelas' => 'required|string|max:20',
            'rombel' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'nullable|string|max:30',
            'nama_wali' => 'nullable|string|max:150',
        ]);

        Siswa::create($data);
        return redirect()->route('admin.siswa')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        Siswa::destroy($id);
        return redirect()->route('admin.siswa')->with('success', 'Data siswa berhasil dihapus.');
    }
}
