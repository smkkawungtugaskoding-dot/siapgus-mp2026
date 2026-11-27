import React, { useState } from 'react';
import { AppDatabase, UserAccount } from '../../types';
import {
  Users,
  Layers,
  BookOpen,
  Calendar,
  Sparkles,
  Award,
  CheckCircle,
  FileSpreadsheet,
  CheckSquare,
  Printer,
  FileText,
  ArrowRight,
  ShieldCheck,
  Camera,
  KeyRound,
  Eye,
  EyeOff,
  AlertCircle,
  CheckCircle2,
  Lock,
  ChevronDown,
  ChevronUp,
  Download,
  Database
} from 'lucide-react';
import { findWaliAssignment } from '../../utils/waliKelas';
import { AvatarUploadCard } from '../AvatarUploadCard';

interface GuruDashboardProps {
  currentUser: UserAccount;
  loginTime: string;
  db: AppDatabase;
  onNavigate: (menu: string) => void;
  onOpenSpreadsheetSync?: () => void;
  onUpdateAvatar?: (avatarUrl: string) => Promise<void> | void;
  onChangePassword?: (
    userId: string,
    oldPass: string,
    newPass: string
  ) => { success: boolean; message: string };
}

export const GuruDashboard: React.FC<GuruDashboardProps> = ({
  currentUser,
  loginTime,
  db,
  onNavigate,
  onOpenSpreadsheetSync,
  onUpdateAvatar,
  onChangePassword,
}) => {
  const { siswa, guru, kelasList, guruMapel, waliKelas, profile } = db;
  const [showProfileSecurity, setShowProfileSecurity] = useState(false);

  // Password state for Guru
  const [currentPassword, setCurrentPassword] = useState('');
  const [newPassword, setNewPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [showCurrentPass, setShowCurrentPass] = useState(false);
  const [showNewPass, setShowNewPass] = useState(false);
  const [showConfirmPass, setShowConfirmPass] = useState(false);
  const [passwordError, setPasswordError] = useState('');
  const [passwordSuccess, setPasswordSuccess] = useState('');
  const [isSubmittingPass, setIsSubmittingPass] = useState(false);

  // Robustly determine if this guru is a designated wali kelas
  const currentWaliAssignment = findWaliAssignment(currentUser, waliKelas);

  // Teaching classes assigned to this guru
  const myAssignedClasses = guruMapel.filter(
    (gm) =>
      gm.namaGuru.toLowerCase().trim() ===
      currentUser.fullName.toLowerCase().trim()
  );

  const totalSiswa = siswa.length;

  const handlePasswordSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setPasswordError('');
    setPasswordSuccess('');

    if (!currentPassword.trim()) {
      setPasswordError('Silakan masukkan kata sandi Anda saat ini.');
      return;
    }
    if (!newPassword.trim()) {
      setPasswordError('Silakan masukkan kata sandi baru.');
      return;
    }
    if (newPassword.trim().length < 3) {
      setPasswordError('Kata sandi baru minimal 3 karakter.');
      return;
    }
    if (newPassword === currentPassword) {
      setPasswordError('Kata sandi baru tidak boleh sama dengan kata sandi lama.');
      return;
    }
    if (newPassword !== confirmPassword) {
      setPasswordError('Konfirmasi kata sandi baru tidak cocok.');
      return;
    }

    if (!onChangePassword) {
      setPasswordError('Layanan pengubahan kata sandi belum tersedia.');
      return;
    }

    setIsSubmittingPass(true);
    try {
      const res = onChangePassword(currentUser.id, currentPassword.trim(), newPassword.trim());
      if (res.success) {
        setPasswordSuccess(res.message);
        setCurrentPassword('');
        setNewPassword('');
        setConfirmPassword('');
        setTimeout(() => setPasswordSuccess(''), 4000);
      } else {
        setPasswordError(res.message);
      }
    } catch {
      setPasswordError('Terjadi kesalahan saat memperbarui kata sandi.');
    } finally {
      setIsSubmittingPass(false);
    }
  };

  const initialLetter = (currentUser.fullName || 'G').charAt(0).toUpperCase();

  return (
    <div className="space-y-6">
      {/* Greeting Banner with Avatar & Profile Quick Trigger */}
      <div className="bg-linear-to-r from-emerald-600 via-teal-600 to-cyan-600 text-white rounded-2xl p-6 sm:p-8 shadow-sm">
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-6">
          <div className="flex items-start sm:items-center gap-4 sm:gap-5">
            {/* Prominent Teacher Avatar Display */}
            <div className="relative shrink-0">
              <div className="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white/20 backdrop-blur-md border-2 border-white/40 shadow-md flex items-center justify-center overflow-hidden font-bold text-2xl text-white">
                {currentUser.avatarUrl ? (
                  <img
                    src={currentUser.avatarUrl}
                    alt={currentUser.fullName}
                    className="w-full h-full object-cover"
                  />
                ) : (
                  <span>{initialLetter}</span>
                )}
              </div>
              <button
                type="button"
                onClick={() => setShowProfileSecurity((prev) => !prev)}
                title="Kelola Foto & Sandi Akun"
                className="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-white text-emerald-700 shadow-md flex items-center justify-center hover:bg-emerald-50 transition border border-emerald-200 cursor-pointer"
              >
                <Camera className="w-3.5 h-3.5" />
              </button>
            </div>

            <div className="space-y-1.5">
              <div className="inline-flex items-center gap-2 px-3 py-0.5 bg-white/15 backdrop-blur-xs rounded-full text-xs font-medium text-emerald-100">
                <Sparkles className="w-3.5 h-3.5 text-amber-300" />
                <span>Sistem Aplikasi Guru Sekolah (SiApgus)</span>
              </div>
              <h1 className="text-2xl sm:text-3xl font-bold tracking-tight">
                Hi, {currentUser.fullName}
              </h1>
              <p className="text-xs sm:text-sm text-emerald-100/90 max-w-2xl leading-relaxed">
                Selamat bertugas di {profile.namaSekolah}. Melalui portal SiApgus, Bapak/Ibu Guru dapat mengelola Jurnal Pembelajaran harian, input penilaian sumatif dan asesmen akhir semester (STS/SAS), serta mencatat absensi dan mencetak buku rapor siswa.
              </p>
            </div>
          </div>

          <div className="flex flex-col gap-2 shrink-0">
            <div className="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20 text-left md:text-right">
              <span className="text-[11px] text-emerald-200 uppercase font-semibold tracking-wider block">
                Waktu Masuk
              </span>
              <div className="flex items-center gap-2 mt-1 text-sm font-semibold text-white">
                <Calendar className="w-4 h-4 text-emerald-200" />
                <span>{loginTime || new Date().toLocaleString('id-ID')}</span>
              </div>
              <div className="mt-1 text-xs text-emerald-100">
                {currentWaliAssignment ? (
                  <span className="inline-flex items-center gap-1 font-bold text-amber-300">
                    <Award className="w-3.5 h-3.5" />
                    Wali Kelas {currentWaliAssignment.kodeKelas}{currentWaliAssignment.rombel}
                  </span>
                ) : (
                  <span>Guru Mata Pelajaran</span>
                )}
              </div>
            </div>

            <button
              type="button"
              onClick={() => setShowProfileSecurity((prev) => !prev)}
              className="inline-flex items-center justify-center gap-2 px-3 py-2 bg-white/20 hover:bg-white/30 active:bg-white/40 text-white rounded-xl text-xs font-semibold backdrop-blur-xs transition cursor-pointer"
            >
              <Camera className="w-3.5 h-3.5 text-amber-300" />
              <span>{showProfileSecurity ? 'Tutup Pengaturan Foto & Sandi' : 'Ganti Foto Avatar & Kata Sandi'}</span>
              {showProfileSecurity ? (
                <ChevronUp className="w-3.5 h-3.5 ml-auto" />
              ) : (
                <ChevronDown className="w-3.5 h-3.5 ml-auto" />
              )}
            </button>
          </div>
        </div>
      </div>

      {/* Profil Guru: Upload Foto (Sebelum & Sesudah) & Ganti Kata Sandi */}
      {showProfileSecurity && (
        <div className="space-y-6 animate-in slide-in-from-top-4 duration-300">
          <div className="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-center justify-between">
            <div className="flex items-center gap-2 text-xs font-semibold text-emerald-900">
              <ShieldCheck className="w-4 h-4 text-emerald-600" />
              <span>Pengaturan Profil Guru: Foto Avatar &amp; Keamanan Kata Sandi</span>
            </div>
            <button
              type="button"
              onClick={() => setShowProfileSecurity(false)}
              className="text-xs text-emerald-700 hover:text-emerald-900 font-medium underline"
            >
              Tutup Pengaturan
            </button>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {/* Foto Avatar Card with Before/After Display */}
            {onUpdateAvatar && (
              <AvatarUploadCard
                currentAvatarUrl={currentUser.avatarUrl}
                userFullName={currentUser.fullName}
                userRole="guru"
                userUsername={currentUser.username}
                onSaveAvatar={onUpdateAvatar}
              />
            )}

            {/* Ganti Kata Sandi Guru */}
            <div className="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
              <div className="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
                <div className="flex items-center gap-3">
                  <div className="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                    <KeyRound className="w-5 h-5 text-emerald-600" />
                  </div>
                  <div>
                    <h3 className="text-sm font-bold text-slate-800">
                      Ganti Kata Sandi Akun Guru
                    </h3>
                    <p className="text-xs text-slate-500">
                      Perbarui kata sandi login untuk keamanan akun portal guru Anda
                    </p>
                  </div>
                </div>
                <span className="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wider">
                  Guru
                </span>
              </div>

              <div className="p-5 sm:p-6 space-y-4">
                {passwordSuccess && (
                  <div className="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs flex items-center gap-2 animate-in fade-in">
                    <CheckCircle2 className="w-4 h-4 text-emerald-600 shrink-0" />
                    <span>{passwordSuccess}</span>
                  </div>
                )}

                {passwordError && (
                  <div className="p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs flex items-center gap-2 animate-in fade-in">
                    <AlertCircle className="w-4 h-4 text-rose-500 shrink-0" />
                    <span>{passwordError}</span>
                  </div>
                )}

                <form onSubmit={handlePasswordSubmit} className="space-y-4">
                  <div>
                    <label className="block text-xs font-semibold text-slate-700 mb-1">
                      Kata Sandi Saat Ini <span className="text-rose-500">*</span>
                    </label>
                    <div className="relative">
                      <input
                        type={showCurrentPass ? 'text' : 'password'}
                        value={currentPassword}
                        onChange={(e) => setCurrentPassword(e.target.value)}
                        placeholder="Masukkan sandi saat ini"
                        className="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-hidden focus:ring-2 focus:ring-emerald-500 focus:bg-white"
                        required
                      />
                      <button
                        type="button"
                        onClick={() => setShowCurrentPass(!showCurrentPass)}
                        className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                      >
                        {showCurrentPass ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                      </button>
                    </div>
                  </div>

                  <div>
                    <label className="block text-xs font-semibold text-slate-700 mb-1">
                      Kata Sandi Baru <span className="text-rose-500">*</span>
                    </label>
                    <div className="relative">
                      <input
                        type={showNewPass ? 'text' : 'password'}
                        value={newPassword}
                        onChange={(e) => setNewPassword(e.target.value)}
                        placeholder="Minimal 3 karakter"
                        className="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-hidden focus:ring-2 focus:ring-emerald-500 focus:bg-white"
                        required
                      />
                      <button
                        type="button"
                        onClick={() => setShowNewPass(!showNewPass)}
                        className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                      >
                        {showNewPass ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                      </button>
                    </div>
                  </div>

                  <div>
                    <label className="block text-xs font-semibold text-slate-700 mb-1">
                      Ulangi Kata Sandi Baru <span className="text-rose-500">*</span>
                    </label>
                    <div className="relative">
                      <input
                        type={showConfirmPass ? 'text' : 'password'}
                        value={confirmPassword}
                        onChange={(e) => setConfirmPassword(e.target.value)}
                        placeholder="Ketik ulang kata sandi baru"
                        className="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-hidden focus:ring-2 focus:ring-emerald-500 focus:bg-white"
                        required
                      />
                      <button
                        type="button"
                        onClick={() => setShowConfirmPass(!showConfirmPass)}
                        className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                      >
                        {showConfirmPass ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                      </button>
                    </div>
                  </div>

                  <button
                    type="submit"
                    disabled={isSubmittingPass}
                    className="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
                  >
                    <Lock className="w-3.5 h-3.5" />
                    <span>{isSubmittingPass ? 'Menyimpan...' : 'Perbarui Kata Sandi Guru'}</span>
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Special Wali Kelas Notice & Quick Action Banner (Hanya ditampilkan untuk Wali Kelas yang ditunjuk) */}
      {(Boolean(currentWaliAssignment) || currentUser.role === 'admin') && (
        <div className="p-4 sm:p-5 bg-linear-to-r from-amber-500/10 via-emerald-500/10 to-blue-500/10 border border-emerald-200 rounded-2xl shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div className="flex items-start sm:items-center gap-3">
            <div className="p-2.5 rounded-xl bg-emerald-600 text-white shadow-xs shrink-0">
              <Award className="w-5 h-5" />
            </div>
            <div>
              <div className="flex items-center gap-2 flex-wrap">
                <h3 className="text-sm font-bold text-slate-900">
                  {currentWaliAssignment
                    ? `Layanan Aktif Wali Kelas ${currentWaliAssignment.kodeKelas} (${currentWaliAssignment.rombel})`
                    : 'Layanan Wali Kelas'}
                </h3>
                <span className="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded-full">
                  {currentWaliAssignment ? 'Tugas Tambahan Terdaftar' : 'Akses Administrator'}
                </span>
              </div>
              <p className="text-xs text-slate-600 mt-0.5">
                Kelola pencatatan kehadiran harian, cetak lembar rekapitulasi absensi bulanan, dan kelola penilaian rapor siswa.
              </p>
            </div>
          </div>

          {/* Direct Action Buttons for Wali Kelas */}
          <div className="flex items-center gap-2 flex-wrap shrink-0">
            <button
              type="button"
              onClick={() => onNavigate('guru-absensi')}
              className="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow-xs transition cursor-pointer"
            >
              <CheckSquare className="w-3.5 h-3.5" />
              <span>Menu Absensi</span>
            </button>
            <button
              type="button"
              onClick={() => onNavigate('guru-cetak-absen')}
              className="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-600 hover:bg-purple-700 active:bg-purple-800 text-white text-xs font-bold rounded-xl shadow-xs transition cursor-pointer"
            >
              <Printer className="w-3.5 h-3.5" />
              <span>Cetak Absen</span>
            </button>
            <button
              type="button"
              onClick={() => onNavigate('guru-penilaian')}
              className="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white text-xs font-bold rounded-xl shadow-xs transition cursor-pointer"
            >
              <FileText className="w-3.5 h-3.5" />
              <span>Cetak Rapor</span>
            </button>
          </div>
        </div>
      )}

      {/* Metrics Row */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {/* Total Siswa */}
        <div className="bg-linear-to-br from-blue-50 to-indigo-50/50 p-5 rounded-2xl border border-blue-200/70 shadow-2xs">
          <div className="flex items-center justify-between">
            <span className="text-xs font-semibold text-blue-800">Total Siswa Terdaftar</span>
            <div className="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center">
              <Users className="w-4 h-4" />
            </div>
          </div>
          <div className="mt-3">
            <div className="text-2xl font-bold text-slate-900 tabular-nums">
              {totalSiswa}
            </div>
            <p className="text-xs text-blue-700/80 mt-1">Siswa Terdata di Sistem</p>
          </div>
        </div>

        {/* Total Kelas Sekolah */}
        <div className="bg-linear-to-br from-emerald-50 to-teal-50/50 p-5 rounded-2xl border border-emerald-200/70 shadow-2xs">
          <div className="flex items-center justify-between">
            <span className="text-xs font-semibold text-emerald-800">Total Kelas Sekolah</span>
            <div className="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center">
              <Layers className="w-4 h-4" />
            </div>
          </div>
          <div className="mt-3">
            <div className="text-2xl font-bold text-slate-900 tabular-nums">
              {kelasList.length}
            </div>
            <p className="text-xs text-emerald-700/80 mt-1">Kelas 7, 8, dan 9</p>
          </div>
        </div>

        {/* Kelas Diampu Guru Ini */}
        <div className="bg-linear-to-br from-purple-50 to-indigo-50/50 p-5 rounded-2xl border border-purple-200/70 shadow-2xs">
          <div className="flex items-center justify-between">
            <span className="text-xs font-semibold text-purple-800">Kelas Diampu Anda</span>
            <div className="w-9 h-9 rounded-xl bg-purple-600 text-white flex items-center justify-center">
              <BookOpen className="w-4 h-4" />
            </div>
          </div>
          <div className="mt-3">
            <div className="text-2xl font-bold text-slate-900 tabular-nums">
              {myAssignedClasses.length}
            </div>
            <p className="text-xs text-purple-700/80 mt-1">Rombongan Belajar</p>
          </div>
        </div>

        {/* Status Wali Kelas */}
        <div className="bg-linear-to-br from-amber-50 to-orange-50/50 p-5 rounded-2xl border border-amber-200/70 shadow-2xs">
          <div className="flex items-center justify-between">
            <span className="text-xs font-semibold text-amber-800">Status Tugas Tambahan</span>
            <div className="w-9 h-9 rounded-xl bg-amber-600 text-white flex items-center justify-center">
              <Award className="w-4 h-4" />
            </div>
          </div>
          <div className="mt-3">
            <div className="text-lg font-bold text-slate-900 truncate">
              {currentWaliAssignment
                ? `Wali Kelas ${currentWaliAssignment.kodeKelas}${currentWaliAssignment.rombel}`
                : 'Wali Kelas / Guru Pengajar'}
            </div>
            <p className="text-xs text-amber-700/80 mt-1">
              Hak Akses Absensi & Cetak Rapor Aktif
            </p>
          </div>
        </div>
      </div>

      {/* Layanan Utama Wali Kelas (Absen, Cetak Absen, Cetak Rapor) */}
      <div className="bg-white rounded-2xl border border-slate-200 p-6 space-y-4">
        <div className="flex items-center justify-between">
          <div>
            <h2 className="text-sm font-bold text-slate-900">
              Layanan Khusus Wali Kelas: Absensi & Cetak Rapor
            </h2>
            <p className="text-xs text-slate-500">
              Pilihan menu utama untuk pengelolaan presensi dan pencetakan dokumen siswa.
            </p>
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
          {/* Card: Menu Absensi Siswa */}
          <div
            onClick={() => onNavigate('guru-absensi')}
            className="p-4 bg-slate-50 hover:bg-emerald-50/70 border border-slate-200 hover:border-emerald-300 rounded-xl cursor-pointer transition space-y-2 group"
          >
            <div className="flex items-center justify-between">
              <div className="flex items-center gap-2 text-emerald-700 font-bold text-xs">
                <div className="p-1.5 rounded-lg bg-emerald-100 text-emerald-700 group-hover:bg-emerald-600 group-hover:text-white transition">
                  <CheckSquare className="w-4 h-4" />
                </div>
                <span>Menu Absensi Siswa Harian</span>
              </div>
              <ArrowRight className="w-3.5 h-3.5 text-slate-400 group-hover:text-emerald-600 group-hover:translate-x-0.5 transition" />
            </div>
            <p className="text-[11px] text-slate-500 leading-relaxed">
              Catat kehadiran harian siswa (Hadir, Sakit, Ijin, Alpha) dengan fitur indikator peringatan visual otomatis untuk kehadiran di bawah 75%.
            </p>
          </div>

          {/* Card: Cetak Absen Siswa */}
          <div
            onClick={() => onNavigate('guru-cetak-absen')}
            className="p-4 bg-slate-50 hover:bg-purple-50/70 border border-slate-200 hover:border-purple-300 rounded-xl cursor-pointer transition space-y-2 group"
          >
            <div className="flex items-center justify-between">
              <div className="flex items-center gap-2 text-purple-700 font-bold text-xs">
                <div className="p-1.5 rounded-lg bg-purple-100 text-purple-700 group-hover:bg-purple-600 group-hover:text-white transition">
                  <Printer className="w-4 h-4" />
                </div>
                <span>Cetak Rekap Absensi Siswa</span>
              </div>
              <ArrowRight className="w-3.5 h-3.5 text-slate-400 group-hover:text-purple-600 group-hover:translate-x-0.5 transition" />
            </div>
            <p className="text-[11px] text-slate-500 leading-relaxed">
              Cetak lembar rekapitulasi presensi bulanan per kelas dalam format tabel resmi berstandar dokumen sekolah yang siap dicetak/PDF.
            </p>
          </div>

          {/* Card: Cetak Rapor Siswa */}
          <div
            onClick={() => onNavigate('guru-cetak-rapor')}
            className="p-4 bg-slate-50 hover:bg-rose-50/70 border border-slate-200 hover:border-rose-300 rounded-xl cursor-pointer transition space-y-2 group"
          >
            <div className="flex items-center justify-between">
              <div className="flex items-center gap-2 text-rose-700 font-bold text-xs">
                <div className="p-1.5 rounded-lg bg-rose-100 text-rose-700 group-hover:bg-rose-600 group-hover:text-white transition">
                  <FileText className="w-4 h-4" />
                </div>
                <span>Cetak Rapor Siswa (PDF)</span>
              </div>
              <ArrowRight className="w-3.5 h-3.5 text-slate-400 group-hover:text-rose-600 group-hover:translate-x-0.5 transition" />
            </div>
            <p className="text-[11px] text-slate-500 leading-relaxed">
              Cetak buku laporan hasil belajar peserta didik (kartu hasil belajar) format A4 resmi dengan data Kepala Sekolah dan lembar pengesahan.
            </p>
          </div>
        </div>
      </div>

      {/* Akses Cepat Menu Kerja Guru */}
      <div className="bg-white rounded-2xl border border-slate-200 p-6 space-y-4">
        <h2 className="text-sm font-bold text-slate-900">
          Akses Menu Pembelajaran Guru
        </h2>
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div
            onClick={() => onNavigate('guru-jurnal')}
            className="p-4 bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-200 rounded-xl cursor-pointer transition space-y-1.5"
          >
            <div className="flex items-center gap-2 text-blue-700 font-semibold text-xs">
              <BookOpen className="w-4 h-4" />
              <span>Jurnal Pembelajaran</span>
            </div>
            <p className="text-[11px] text-slate-500">
              Isi CP, TP, KBM, dan refleksi pembelajaran harian siswa.
            </p>
          </div>

          <div
            onClick={() => onNavigate('guru-penilaian')}
            className="p-4 bg-slate-50 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-200 rounded-xl cursor-pointer transition space-y-1.5"
          >
            <div className="flex items-center gap-2 text-emerald-700 font-semibold text-xs">
              <FileSpreadsheet className="w-4 h-4" />
              <span>Data Penilaian Rapor</span>
            </div>
            <p className="text-[11px] text-slate-500">
              Input Sumatif 1, 2, 3, STS, SAS, dan hitung nilai akhir otomatis.
            </p>
          </div>
        </div>

        <div className="pt-2 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
          <div className="text-xs text-slate-600">
            Unduh aplikasi SiApgus siap pakai atau sinkronkan data ke Google Spreadsheet.
          </div>
          <div className="flex flex-wrap items-center gap-2">
            <a
              href="/api/download-htdocs"
              download="siapgus-aplikasi.zip"
              className="inline-flex items-center gap-1.5 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl transition whitespace-nowrap"
            >
              <Download className="w-3.5 h-3.5" />
              <span>Download Aplikasi (.zip)</span>
            </a>
            <a
              href="/Index.html"
              download="Index.html"
              className="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold rounded-xl transition whitespace-nowrap"
            >
              <Download className="w-3.5 h-3.5 text-slate-600" />
              <span>Download Index.html</span>
            </a>
            {onOpenSpreadsheetSync && (
              <button
                type="button"
                onClick={onOpenSpreadsheetSync}
                className="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl transition cursor-pointer whitespace-nowrap"
              >
                <Database className="w-3.5 h-3.5" />
                <span>Integrasi Spreadsheet</span>
              </button>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};
