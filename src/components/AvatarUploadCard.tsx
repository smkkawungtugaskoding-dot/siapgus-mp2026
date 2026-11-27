import React, { useState, useRef } from 'react';
import { optimizeAvatarImage } from '../utils/imageOptimizer';
import {
  Camera,
  Upload,
  CheckCircle2,
  AlertCircle,
  X,
  Trash2,
  Sparkles,
  ArrowRight,
  User,
  ShieldCheck
} from 'lucide-react';

interface AvatarUploadCardProps {
  currentAvatarUrl?: string;
  userFullName: string;
  userRole: 'admin' | 'guru';
  userUsername: string;
  onSaveAvatar: (newAvatarUrl: string) => Promise<void> | void;
  onRemoveAvatar?: () => Promise<void> | void;
}

export const AvatarUploadCard: React.FC<AvatarUploadCardProps> = ({
  currentAvatarUrl,
  userFullName,
  userRole,
  userUsername,
  onSaveAvatar,
  onRemoveAvatar,
}) => {
  const [selectedPreview, setSelectedPreview] = useState<string | null>(null);
  const [isProcessing, setIsProcessing] = useState(false);
  const [statusMessage, setStatusMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);
  const fileInputRef = useRef<HTMLInputElement>(null);

  const handleFileChange = async (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;

    if (!file.type.startsWith('image/')) {
      setStatusMessage({ type: 'error', text: 'Format file tidak didukung. Harap pilih file gambar (JPG, PNG, atau WebP).' });
      return;
    }

    if (file.size > 5 * 1024 * 1024) {
      setStatusMessage({ type: 'error', text: 'Ukuran file terlalu besar. Maksimal 5 MB.' });
      return;
    }

    setIsProcessing(true);
    setStatusMessage(null);

    try {
      const optimized = await optimizeAvatarImage(file, 220);
      setSelectedPreview(optimized);
    } catch {
      setStatusMessage({ type: 'error', text: 'Gagal memproses gambar. Silakan gunakan gambar lain.' });
    } finally {
      setIsProcessing(false);
    }
  };

  const handleCancelSelection = () => {
    setSelectedPreview(null);
    if (fileInputRef.current) fileInputRef.current.value = '';
    setStatusMessage(null);
  };

  const handleConfirmSave = async () => {
    if (!selectedPreview) return;
    setIsProcessing(true);
    setStatusMessage(null);
    try {
      await onSaveAvatar(selectedPreview);
      setStatusMessage({ type: 'success', text: 'Foto avatar profil berhasil diperbarui dan disimpan!' });
      setSelectedPreview(null);
      if (fileInputRef.current) fileInputRef.current.value = '';
      setTimeout(() => setStatusMessage(null), 3500);
    } catch {
      setStatusMessage({ type: 'error', text: 'Terjadi kesalahan saat menyimpan foto avatar.' });
    } finally {
      setIsProcessing(false);
    }
  };

  const handleRemove = async () => {
    if (!window.confirm('Apakah Anda yakin ingin menghapus foto profil dan kembali menggunakan avatar bawaan?')) {
      return;
    }
    setIsProcessing(true);
    try {
      if (onRemoveAvatar) {
        await onRemoveAvatar();
      } else {
        await onSaveAvatar('');
      }
      setSelectedPreview(null);
      setStatusMessage({ type: 'success', text: 'Foto avatar telah dihapus, kembali ke avatar bawaan.' });
      setTimeout(() => setStatusMessage(null), 3500);
    } catch {
      setStatusMessage({ type: 'error', text: 'Gagal menghapus foto avatar.' });
    } finally {
      setIsProcessing(false);
    }
  };

  const firstLetter = (userFullName || 'U').charAt(0).toUpperCase();

  return (
    <div className="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
      {/* Header */}
      <div className="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
        <div className="flex items-center gap-3">
          <div className="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
            <Camera className="w-5 h-5 text-blue-600" />
          </div>
          <div>
            <h3 className="text-sm font-bold text-slate-800">
              Foto Profil &amp; Avatar Akun {userRole === 'admin' ? 'Administrator' : 'Guru'}
            </h3>
            <p className="text-xs text-slate-500">
              Kelola foto profil Anda untuk identitas resmi pada portal SiApgus
            </p>
          </div>
        </div>
        <span className="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 uppercase tracking-wider">
          {userRole}
        </span>
      </div>

      <div className="p-5 sm:p-6 space-y-6">
        {/* Status Alerts */}
        {statusMessage && (
          <div
            className={`p-3 rounded-xl text-xs flex items-center gap-2.5 animate-in fade-in ${
              statusMessage.type === 'success'
                ? 'bg-emerald-50 border border-emerald-200 text-emerald-800'
                : 'bg-rose-50 border border-rose-200 text-rose-700'
            }`}
          >
            {statusMessage.type === 'success' ? (
              <CheckCircle2 className="w-4 h-4 text-emerald-600 shrink-0" />
            ) : (
              <AlertCircle className="w-4 h-4 text-rose-500 shrink-0" />
            )}
            <span>{statusMessage.text}</span>
          </div>
        )}

        {/* Visual Comparison: Sebelum (Current) vs Sesudah (Preview Baru) */}
        <div className="flex flex-col md:flex-row items-center gap-6 p-4 bg-slate-50/80 rounded-2xl border border-slate-200/80">
          
          {/* Card 1: Foto Avatar Saat Ini (Sebelum Diganti) */}
          <div className="flex flex-col items-center text-center space-y-2">
            <span className="text-[11px] font-bold text-slate-500 uppercase tracking-wider">
              Foto Saat Ini (Sebelum Diganti)
            </span>
            <div className="relative w-28 h-28 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white font-black text-3xl flex items-center justify-center shadow-md overflow-hidden border-2 border-white ring-2 ring-slate-200">
              {currentAvatarUrl ? (
                <img
                  src={currentAvatarUrl}
                  alt={userFullName}
                  className="w-full h-full object-cover"
                />
              ) : (
                <span>{firstLetter}</span>
              )}
            </div>
            <div className="text-[11px] text-slate-500 font-medium">
              {currentAvatarUrl ? 'Foto kustom aktif' : 'Avatar bawaan (Monogram)'}
            </div>
          </div>

          {/* Arrow Indicator if previewing new photo */}
          {selectedPreview ? (
            <div className="flex flex-col items-center justify-center text-blue-600 gap-1 animate-pulse">
              <span className="text-[10px] font-bold bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                Pratinjau
              </span>
              <ArrowRight className="w-6 h-6 hidden md:block" />
              <span className="md:hidden text-xs font-bold">Menggantikan dengan:</span>
            </div>
          ) : (
            <div className="hidden md:block w-px h-28 bg-slate-200" />
          )}

          {/* Card 2: Foto Baru (Sebelum Disimpan) */}
          {selectedPreview && (
            <div className="flex flex-col items-center text-center space-y-2 animate-in zoom-in-95 duration-200">
              <span className="text-[11px] font-bold text-blue-600 uppercase tracking-wider flex items-center gap-1">
                <Sparkles className="w-3 h-3 text-amber-500" />
                Pratinjau Foto Baru (Belum Disimpan)
              </span>
              <div className="relative w-28 h-28 rounded-2xl bg-white shadow-lg overflow-hidden border-2 border-blue-500 ring-4 ring-blue-100">
                <img
                  src={selectedPreview}
                  alt="Pratinjau Foto Baru"
                  className="w-full h-full object-cover"
                />
              </div>
              <div className="text-[11px] text-blue-700 font-bold">
                Siap disimpan
              </div>
            </div>
          )}

          {/* Upload Controls & Instructions */}
          <div className="flex-1 space-y-3 w-full">
            <div className="space-y-1">
              <h4 className="text-xs font-bold text-slate-800">
                {selectedPreview ? 'Konfirmasi Foto Profil Baru' : 'Unggah Foto Baru'}
              </h4>
              <p className="text-[11px] text-slate-500 leading-relaxed">
                Foto akan otomatis disesuaikan secara proporsional dan ditampilkan pada bilah atas (*Navbar*), bilah samping (*Sidebar*), serta dokumen rapor. Format yang didukung: JPG, PNG, atau WebP (maks. 5 MB).
              </p>
            </div>

            {/* Hidden File Input */}
            <input
              ref={fileInputRef}
              type="file"
              accept="image/jpeg,image/png,image/webp"
              onChange={handleFileChange}
              className="hidden"
            />

            {/* Action Buttons */}
            {selectedPreview ? (
              <div className="flex flex-wrap items-center gap-2 pt-2">
                <button
                  type="button"
                  onClick={handleConfirmSave}
                  disabled={isProcessing}
                  className="px-4 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/20 transition flex items-center gap-1.5 cursor-pointer disabled:opacity-50"
                >
                  <CheckCircle2 className="w-4 h-4" />
                  <span>{isProcessing ? 'Menyimpan...' : '✓ Simpan & Pasang Foto'}</span>
                </button>
                <button
                  type="button"
                  onClick={handleCancelSelection}
                  disabled={isProcessing}
                  className="px-3.5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-xs font-semibold transition cursor-pointer"
                >
                  ✕ Batalkan
                </button>
              </div>
            ) : (
              <div className="flex flex-wrap items-center gap-2 pt-2">
                <button
                  type="button"
                  onClick={() => fileInputRef.current?.click()}
                  disabled={isProcessing}
                  className="px-4 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/20 transition flex items-center gap-1.5 cursor-pointer disabled:opacity-50"
                >
                  <Upload className="w-4 h-4" />
                  <span>Pilih Berkas Foto Baru</span>
                </button>

                {currentAvatarUrl && (
                  <button
                    type="button"
                    onClick={handleRemove}
                    disabled={isProcessing}
                    className="px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 cursor-pointer"
                    title="Hapus foto kustom dan gunakan avatar bawaan"
                  >
                    <Trash2 className="w-3.5 h-3.5" />
                    <span>Hapus Foto</span>
                  </button>
                )}
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};
