import React, { useState } from 'react';
import { UserAccount } from '../../types';
import {
  KeyRound,
  Eye,
  EyeOff,
  CheckCircle2,
  AlertCircle,
  Lock,
  ShieldCheck
} from 'lucide-react';

interface AdminPasswordChangeCardProps {
  currentUser: UserAccount;
  onChangePassword: (
    userId: string,
    oldPass: string,
    newPass: string
  ) => { success: boolean; message: string };
}

export const AdminPasswordChangeCard: React.FC<AdminPasswordChangeCardProps> = ({
  currentUser,
  onChangePassword,
}) => {
  const [currentPassword, setCurrentPassword] = useState('');
  const [newPassword, setNewPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');

  const [showCurrentPassword, setShowCurrentPassword] = useState(false);
  const [showNewPassword, setShowNewPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);

  const [errorMessage, setErrorMessage] = useState('');
  const [successMessage, setSuccessMessage] = useState('');
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setErrorMessage('');
    setSuccessMessage('');

    if (!currentPassword.trim()) {
      setErrorMessage('Silakan masukkan kata sandi saat ini.');
      return;
    }

    if (!newPassword.trim()) {
      setErrorMessage('Silakan masukkan kata sandi baru.');
      return;
    }

    if (newPassword.trim().length < 3) {
      setErrorMessage('Kata sandi baru minimal harus terdiri dari 3 karakter.');
      return;
    }

    if (newPassword === currentPassword) {
      setErrorMessage('Kata sandi baru tidak boleh sama dengan kata sandi lama.');
      return;
    }

    if (newPassword !== confirmPassword) {
      setErrorMessage('Konfirmasi kata sandi baru tidak cocok. Silakan periksa kembali.');
      return;
    }

    setIsSubmitting(true);
    try {
      const res = onChangePassword(currentUser.id, currentPassword.trim(), newPassword.trim());
      if (res.success) {
        setSuccessMessage(res.message);
        setCurrentPassword('');
        setNewPassword('');
        setConfirmPassword('');
        setTimeout(() => setSuccessMessage(''), 4000);
      } else {
        setErrorMessage(res.message);
      }
    } catch {
      setErrorMessage('Terjadi kesalahan saat memperbarui kata sandi administrator.');
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
      {/* Header */}
      <div className="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
        <div className="flex items-center gap-3">
          <div className="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold">
            <KeyRound className="w-5 h-5 text-amber-600" />
          </div>
          <div>
            <h3 className="text-sm font-bold text-slate-800">
              Ganti Kata Sandi Administrator
            </h3>
            <p className="text-xs text-slate-500">
              Perbarui kata sandi akun login Administrator untuk menjaga keamanan data sekolah
            </p>
          </div>
        </div>
        <span className="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 uppercase tracking-wider">
          Keamanan Akun
        </span>
      </div>

      <form onSubmit={handleSubmit} className="p-5 sm:p-6 space-y-4">
        {/* Alerts */}
        {errorMessage && (
          <div className="p-3 bg-rose-50 border border-rose-200 rounded-xl flex items-start gap-2.5 text-xs text-rose-700 animate-in fade-in">
            <AlertCircle className="w-4 h-4 shrink-0 mt-0.5 text-rose-500" />
            <span>{errorMessage}</span>
          </div>
        )}

        {successMessage && (
          <div className="p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-start gap-2.5 text-xs text-emerald-800 animate-in fade-in">
            <CheckCircle2 className="w-4 h-4 shrink-0 mt-0.5 text-emerald-600" />
            <span>{successMessage}</span>
          </div>
        )}

        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
          {/* Current Password */}
          <div>
            <label className="block text-xs font-bold text-slate-700 mb-1.5">
              Kata Sandi Saat Ini <span className="text-red-500">*</span>
            </label>
            <div className="relative">
              <input
                type={showCurrentPassword ? 'text' : 'password'}
                required
                autoComplete="current-password"
                placeholder="Masukkan kata sandi lama"
                value={currentPassword}
                onChange={(e) => setCurrentPassword(e.target.value)}
                className="w-full px-3.5 py-2.5 pr-10 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
              />
              <button
                type="button"
                tabIndex={-1}
                onClick={() => setShowCurrentPassword(!showCurrentPassword)}
                className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 cursor-pointer"
                title={showCurrentPassword ? 'Sembunyikan' : 'Lihat'}
              >
                {showCurrentPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
              </button>
            </div>
          </div>

          {/* New Password */}
          <div>
            <label className="block text-xs font-bold text-slate-700 mb-1.5">
              Kata Sandi Baru <span className="text-red-500">*</span>
            </label>
            <div className="relative">
              <input
                type={showNewPassword ? 'text' : 'password'}
                required
                autoComplete="new-password"
                placeholder="Minimal 3 karakter"
                value={newPassword}
                onChange={(e) => setNewPassword(e.target.value)}
                className="w-full px-3.5 py-2.5 pr-10 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
              />
              <button
                type="button"
                tabIndex={-1}
                onClick={() => setShowNewPassword(!showNewPassword)}
                className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 cursor-pointer"
                title={showNewPassword ? 'Sembunyikan' : 'Lihat'}
              >
                {showNewPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
              </button>
            </div>
          </div>

          {/* Confirm Password */}
          <div>
            <div className="flex items-center justify-between mb-1.5">
              <label className="block text-xs font-bold text-slate-700">
                Konfirmasi Sandi Baru <span className="text-red-500">*</span>
              </label>
              {newPassword && confirmPassword && (
                <span className={`text-[10px] font-bold ${newPassword === confirmPassword ? 'text-emerald-600' : 'text-rose-500'}`}>
                  {newPassword === confirmPassword ? '✓ Cocok' : 'Belum cocok'}
                </span>
              )}
            </div>
            <div className="relative">
              <input
                type={showConfirmPassword ? 'text' : 'password'}
                required
                autoComplete="new-password"
                placeholder="Ulangi kata sandi baru"
                value={confirmPassword}
                onChange={(e) => setConfirmPassword(e.target.value)}
                className="w-full px-3.5 py-2.5 pr-10 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
              />
              <button
                type="button"
                tabIndex={-1}
                onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 cursor-pointer"
                title={showConfirmPassword ? 'Sembunyikan' : 'Lihat'}
              >
                {showConfirmPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
              </button>
            </div>
          </div>
        </div>

        <div className="pt-2 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 border-t border-slate-100">
          <div className="flex items-center gap-2 text-[11px] text-slate-500">
            <ShieldCheck className="w-4 h-4 text-emerald-600 shrink-0" />
            <span>Kata sandi baru akan langsung berlaku untuk login berikutnya di semua perangkat.</span>
          </div>

          <button
            type="submit"
            disabled={isSubmitting}
            className="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 active:bg-amber-800 text-white rounded-xl text-xs font-bold shadow-md shadow-amber-500/20 transition flex items-center gap-1.5 cursor-pointer disabled:opacity-50 shrink-0"
          >
            <Lock className="w-4 h-4" />
            <span>{isSubmitting ? 'Menyimpan...' : 'Simpan Kata Sandi Administrator'}</span>
          </button>
        </div>
      </form>
    </div>
  );
};
