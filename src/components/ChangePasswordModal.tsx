import React, { useState } from 'react';
import { UserAccount } from '../types';
import {
  KeyRound,
  Eye,
  EyeOff,
  CheckCircle2,
  AlertCircle,
  X,
  ShieldCheck,
  Lock,
  UserCheck
} from 'lucide-react';

interface ChangePasswordModalProps {
  isOpen: boolean;
  onClose: () => void;
  currentUser: UserAccount;
  onChangePassword: (
    userId: string,
    oldPassword: string,
    newPassword: string
  ) => { success: boolean; message: string };
}

export const ChangePasswordModal: React.FC<ChangePasswordModalProps> = ({
  isOpen,
  onClose,
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

  if (!isOpen) return null;

  const resetForm = () => {
    setCurrentPassword('');
    setNewPassword('');
    setConfirmPassword('');
    setShowCurrentPassword(false);
    setShowNewPassword(false);
    setShowConfirmPassword(false);
    setErrorMessage('');
    setSuccessMessage('');
  };

  const handleClose = () => {
    resetForm();
    onClose();
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setErrorMessage('');
    setSuccessMessage('');

    if (!currentPassword.trim()) {
      setErrorMessage('Silakan masukkan password saat ini.');
      return;
    }

    if (!newPassword.trim()) {
      setErrorMessage('Silakan masukkan password baru.');
      return;
    }

    if (newPassword.trim().length < 3) {
      setErrorMessage('Password baru minimal harus terdiri dari 3 karakter.');
      return;
    }

    if (newPassword === currentPassword) {
      setErrorMessage('Password baru tidak boleh sama dengan password saat ini.');
      return;
    }

    if (newPassword !== confirmPassword) {
      setErrorMessage('Konfirmasi password baru tidak cocok. Periksa kembali.');
      return;
    }

    setIsSubmitting(true);

    try {
      const result = onChangePassword(
        currentUser.id,
        currentPassword.trim(),
        newPassword.trim()
      );

      if (result.success) {
        setSuccessMessage(result.message);
        setTimeout(() => {
          handleClose();
        }, 1500);
      } else {
        setErrorMessage(result.message);
      }
    } catch {
      setErrorMessage('Terjadi kesalahan saat memperbarui password. Silakan coba lagi.');
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-in fade-in duration-200">
      <div className="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col">
        {/* Modal Header */}
        <div className="p-5 bg-gradient-to-r from-blue-700 via-indigo-700 to-blue-800 text-white flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-md flex items-center justify-center border border-white/20 shadow-inner">
              <KeyRound className="w-5 h-5 text-white" />
            </div>
            <div>
              <h3 className="text-base font-bold text-white tracking-tight">
                Ganti Kata Sandi
              </h3>
              <p className="text-xs text-blue-100">
                Perbarui password akun Anda untuk menjaga keamanan
              </p>
            </div>
          </div>
          <button
            type="button"
            onClick={handleClose}
            className="p-1.5 text-white/70 hover:text-white hover:bg-white/10 rounded-lg transition cursor-pointer"
            title="Tutup"
          >
            <X className="w-5 h-5" />
          </button>
        </div>

        {/* User Identity Info Card */}
        <div className="p-4 bg-slate-50 border-b border-slate-200">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 rounded-xl bg-blue-600 text-white font-extrabold text-sm flex items-center justify-center shadow-xs">
              {currentUser.fullName.charAt(0).toUpperCase()}
            </div>
            <div className="flex-1 min-w-0">
              <div className="flex items-center gap-2">
                <h4 className="text-xs font-bold text-slate-800 truncate">
                  {currentUser.fullName}
                </h4>
                <span className="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">
                  {currentUser.role === 'admin' ? 'Administrator' : 'Guru'}
                </span>
              </div>
              <p className="text-[11px] text-slate-500 font-mono mt-0.5">
                Username:{' '}
                <span className="font-semibold text-slate-700">
                  {currentUser.username}
                </span>
                {currentUser.email && ` · ${currentUser.email}`}
              </p>
            </div>
          </div>
        </div>

        {/* Modal Body / Form */}
        <form onSubmit={handleSubmit} className="p-6 space-y-4">
          {/* Alerts */}
          {errorMessage && (
            <div className="p-3 bg-red-50 border border-red-200 rounded-xl flex items-start gap-2.5 text-xs text-red-700 animate-in fade-in duration-150">
              <AlertCircle className="w-4 h-4 shrink-0 mt-0.5 text-red-500" />
              <span>{errorMessage}</span>
            </div>
          )}

          {successMessage && (
            <div className="p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-start gap-2.5 text-xs text-emerald-800 animate-in fade-in duration-150">
              <CheckCircle2 className="w-4 h-4 shrink-0 mt-0.5 text-emerald-600" />
              <span>{successMessage}</span>
            </div>
          )}

          {/* Current Password */}
          <div>
            <label className="block text-xs font-bold text-slate-700 mb-1.5">
              Password Saat Ini (Lama) <span className="text-red-500">*</span>
            </label>
            <div className="relative">
              <input
                type={showCurrentPassword ? 'text' : 'password'}
                required
                autoComplete="current-password"
                placeholder="Masukkan password saat ini"
                value={currentPassword}
                onChange={(e) => setCurrentPassword(e.target.value)}
                className="w-full px-3.5 py-2.5 pr-10 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:bg-white transition placeholder:text-slate-400"
              />
              <button
                type="button"
                tabIndex={-1}
                onClick={() => setShowCurrentPassword(!showCurrentPassword)}
                className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-hidden p-1 cursor-pointer"
                title={showCurrentPassword ? 'Sembunyikan' : 'Lihat password'}
              >
                {showCurrentPassword ? (
                  <EyeOff className="w-4 h-4" />
                ) : (
                  <Eye className="w-4 h-4" />
                )}
              </button>
            </div>
          </div>

          {/* New Password */}
          <div>
            <label className="block text-xs font-bold text-slate-700 mb-1.5">
              Password Baru <span className="text-red-500">*</span>
            </label>
            <div className="relative">
              <input
                type={showNewPassword ? 'text' : 'password'}
                required
                autoComplete="new-password"
                placeholder="Minimal 3 karakter"
                value={newPassword}
                onChange={(e) => setNewPassword(e.target.value)}
                className="w-full px-3.5 py-2.5 pr-10 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:bg-white transition placeholder:text-slate-400"
              />
              <button
                type="button"
                tabIndex={-1}
                onClick={() => setShowNewPassword(!showNewPassword)}
                className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-hidden p-1 cursor-pointer"
                title={showNewPassword ? 'Sembunyikan' : 'Lihat password'}
              >
                {showNewPassword ? (
                  <EyeOff className="w-4 h-4" />
                ) : (
                  <Eye className="w-4 h-4" />
                )}
              </button>
            </div>
          </div>

          {/* Confirm New Password */}
          <div>
            <div className="flex items-center justify-between mb-1.5">
              <label className="block text-xs font-bold text-slate-700">
                Konfirmasi Password Baru <span className="text-red-500">*</span>
              </label>
              {newPassword && confirmPassword && (
                <span
                  className={`text-[11px] font-semibold flex items-center gap-1 ${
                    newPassword === confirmPassword
                      ? 'text-emerald-600'
                      : 'text-rose-500'
                  }`}
                >
                  {newPassword === confirmPassword ? (
                    <>
                      <CheckCircle2 className="w-3 h-3" />
                      Cocok
                    </>
                  ) : (
                    <>
                      <AlertCircle className="w-3 h-3" />
                      Belum cocok
                    </>
                  )}
                </span>
              )}
            </div>
            <div className="relative">
              <input
                type={showConfirmPassword ? 'text' : 'password'}
                required
                autoComplete="new-password"
                placeholder="Ulangi password baru Anda"
                value={confirmPassword}
                onChange={(e) => setConfirmPassword(e.target.value)}
                className={`w-full px-3.5 py-2.5 pr-10 bg-slate-50 border rounded-xl text-xs text-slate-800 focus:outline-hidden focus:ring-2 focus:bg-white transition placeholder:text-slate-400 ${
                  confirmPassword && newPassword !== confirmPassword
                    ? 'border-rose-300 focus:ring-rose-500'
                    : 'border-slate-300 focus:ring-blue-500'
                }`}
              />
              <button
                type="button"
                tabIndex={-1}
                onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-hidden p-1 cursor-pointer"
                title={showConfirmPassword ? 'Sembunyikan' : 'Lihat password'}
              >
                {showConfirmPassword ? (
                  <EyeOff className="w-4 h-4" />
                ) : (
                  <Eye className="w-4 h-4" />
                )}
              </button>
            </div>
          </div>

          {/* Security Notice */}
          <div className="p-3 bg-blue-50/70 border border-blue-100 rounded-xl flex items-start gap-2 text-[11px] text-blue-800">
            <ShieldCheck className="w-4 h-4 shrink-0 text-blue-600 mt-0.5" />
            <span>
              Perubahan password akan langsung disimpan ke database server dan disinkronkan ke seluruh sesi login Anda.
            </span>
          </div>

          {/* Action Buttons */}
          <div className="pt-2 flex items-center justify-end gap-2.5 border-t border-slate-100">
            <button
              type="button"
              onClick={handleClose}
              disabled={isSubmitting}
              className="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition cursor-pointer"
            >
              Batal
            </button>
            <button
              type="submit"
              disabled={isSubmitting}
              className="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-500/20 transition flex items-center gap-1.5 cursor-pointer disabled:opacity-50"
            >
              <Lock className="w-3.5 h-3.5" />
              <span>{isSubmitting ? 'Menyimpan...' : 'Simpan Password Baru'}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};
