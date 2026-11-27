@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Pengaturan Profil Sekolah &amp; Logo Favicon</h2>
        <p class="text-xs text-slate-500">Ubah identitas lembaga, logo sekolah, kop surat, dan ikon tab browser</p>
    </div>

    <form action="{{ route('admin.profil.update') }}" method="POST" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-5">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Resmi Sekolah</label>
                <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah', $profil->nama_sekolah) }}" required
                       class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Pokok Sekolah Nasional (NPSN)</label>
                <input type="text" name="npsn" value="{{ old('npsn', $profil->npsn) }}"
                       class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Lengkap Sekolah</label>
                <textarea name="alamat_sekolah" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-blue-500">{{ old('alamat_sekolah', $profil->alamat_sekolah) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Telepon / Kontak</label>
                <input type="text" name="telp" value="{{ old('telp', $profil->telp) }}"
                       class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Kurikulum Aktif</label>
                <input type="text" name="kurikulum" value="{{ old('kurikulum', $profil->kurikulum) }}" required
                       class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Kepala Sekolah</label>
                <input type="text" name="kepala_nama" value="{{ old('kepala_nama', $profil->kepala_nama) }}"
                       class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">NIP Kepala Sekolah</label>
                <input type="text" name="kepala_nip" value="{{ old('kepala_nip', $profil->kepala_nip) }}"
                       class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran', $profil->tahun_ajaran) }}" required
                       class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Semester</label>
                <select name="semester" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-blue-500">
                    <option value="Ganjil" {{ $profil->semester === 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                    <option value="Genap" {{ $profil->semester === 'Genap' ? 'selected' : '' }}>Genap</option>
                </select>
            </div>
        </div>

        <!-- UPLOAD LOGO SEKOLAH & FAVICON DINAMIS -->
        <div class="pt-4 border-t border-slate-100 space-y-3">
            <label class="block text-xs font-bold text-slate-700">Logo Sekolah &amp; Ikon Favicon Browser</label>
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden shrink-0 shadow-xs">
                    <img id="logoPreview" src="{{ $profil->logo_url ?: asset('favicon.ico') }}" alt="Preview" class="max-w-full max-h-full object-contain">
                </div>
                <div class="flex-1 space-y-1">
                    <input type="file" id="logoFileInput" accept="image/*" class="text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    <p class="text-[10px] text-slate-400">Pilih gambar logo format PNG atau JPG. Logo akan otomatis menjadi favicon tab browser saat disimpan.</p>
                </div>
            </div>
            <input type="hidden" name="logo_url" id="logoUrlField" value="{{ $profil->logo_url }}">
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                Simpan Profil &amp; Perbarui Favicon
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.getElementById('logoFileInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(evt) {
                const base64 = evt.target.result;
                document.getElementById('logoPreview').src = base64;
                document.getElementById('logoUrlField').value = base64;
                
                // Update dynamic favicon instantly on tab
                const fav = document.getElementById('appFavicon');
                if (fav) fav.href = base64;
                const favS = document.getElementById('appFaviconShortcut');
                if (favS) favS.href = base64;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection
