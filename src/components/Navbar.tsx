import React, { useState, useRef, useEffect } from 'react';
import { AppDatabase, SchoolProfile, UserAccount } from '../types';
import {
  Menu,
  LogOut,
  Database,
  RefreshCw,
  CheckCircle2,
  Download,
  FileCode,
  FileJson,
  FolderDown,
  ChevronDown,
  Globe
} from 'lucide-react';

interface NavbarProps {
  onToggleSidebar: () => void;
  currentUser: UserAccount;
  profile: SchoolProfile;
  db: AppDatabase;
  activeMenu: string;
  onLogout: () => void;
  onOpenSpreadsheetSync: () => void;
  onChangePasswordClick?: () => void;
  isSyncing?: boolean;
  lastSyncedToSheets?: string;
  hasSheetsUrl?: boolean;
  onQuickSync?: () => Promise<void>;
}

export const Navbar: React.FC<NavbarProps> = ({
  onToggleSidebar,
  currentUser,
  profile,
  db,
  activeMenu,
  onLogout,
  onOpenSpreadsheetSync,
  isSyncing = false,
  lastSyncedToSheets,
  hasSheetsUrl = false,
  onQuickSync,
}) => {
  const [downloadMenuOpen, setDownloadMenuOpen] = useState(false);
  const downloadDropdownRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const handleClickOutside = (e: MouseEvent) => {
      if (
        downloadDropdownRef.current &&
        !downloadDropdownRef.current.contains(e.target as Node)
      ) {
        setDownloadMenuOpen(false);
      }
    };
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  // Format breadcrumb title
  const getBreadcrumbTitle = (menu: string) => {
    switch (menu) {
      case 'admin-dashboard':
        return 'Dashboard Administrator';
      case 'admin-siswa':
        return 'Manajemen Data Siswa';
      case 'admin-guru':
        return 'Manajemen Data Guru';
      case 'admin-mapel':
        return 'Guru Mata Pelajaran';
      case 'admin-wali-kelas':
        return 'Penetapan Wali Kelas';
      case 'admin-setting':
        return 'Pengaturan Profil Sekolah';
      case 'guru-dashboard':
        return 'Dashboard Guru';
      case 'guru-jurnal':
        return 'Jurnal Pembelajaran Harian';
      case 'guru-penilaian':
        return 'Data Penilaian Rapor';
      case 'guru-absensi':
        return 'Absensi Siswa Harian';
      case 'guru-cetak-absen':
        return 'Cetak Rekap Absensi';
      case 'guru-cetak-rapor':
        return 'Cetak Rapor Siswa';
      default:
        return 'Sistem Informasi Guru';
    }
  };

  const formatShortTime = (isoStr?: string) => {
    if (!isoStr) return '';
    try {
      const d = new Date(isoStr);
      return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    } catch {
      return '';
    }
  };

  const triggerFileDownload = (href: string, filename: string) => {
    const link = document.createElement('a');
    link.href = href;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    setDownloadMenuOpen(false);
  };

  const handleDownloadJsonBackup = () => {
    const blob = new Blob([JSON.stringify(db, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    triggerFileDownload(url, `siapgus_backup_${new Date().toISOString().slice(0, 10)}.json`);
    setTimeout(() => URL.revokeObjectURL(url), 1000);
  };

  return (
    <header className="h-16 bg-white border-b border-slate-200 px-4 sm:px-6 flex items-center justify-between sticky top-0 z-20">
      {/* Left: 3-line hamburger button + breadcrumb */}
      <div className="flex items-center gap-3 min-w-0">
        <button
          type="button"
          data-sidebar-toggle="true"
          onClick={onToggleSidebar}
          aria-label="Buka tutup sidebar"
          className="p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition cursor-pointer shrink-0"
          title="Buka / Tutup Sidebar"
        >
          <Menu className="w-5 h-5" />
        </button>

        {/* Brand identity on mobile, Breadcrumb on desktop */}
        <div className="flex items-center gap-2 min-w-0">
          <div className="hidden sm:flex items-center gap-2 text-xs text-slate-500 min-w-0">
            <span className="font-semibold text-slate-700 whitespace-nowrap">SiApgus</span>
            <span>/</span>
            <span className="text-blue-600 font-bold truncate">
              {getBreadcrumbTitle(activeMenu)}
            </span>
          </div>

          <div className="sm:hidden flex items-center gap-2 min-w-0">
            <span className="text-xs font-bold text-slate-900 truncate max-w-32">
              {profile.namaSekolah || 'SiApgus'}
            </span>
          </div>
        </div>
      </div>

      {/* Right: Download Aplikasi Menu, Spreadsheet Sync & User badge */}
      <div className="flex items-center gap-2 shrink-0">
        {/* Download Aplikasi Dropdown Button */}
        <div className="relative" ref={downloadDropdownRef}>
          <button
            type="button"
            onClick={() => setDownloadMenuOpen((prev) => !prev)}
            className="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition cursor-pointer whitespace-nowrap shrink-0 shadow-2xs"
            title="Download Aplikasi SiApgus & Script Integrasi Spreadsheet"
          >
            <Download className="w-3.5 h-3.5 shrink-0" />
            <span>Download Aplikasi</span>
            <ChevronDown className={`w-3.5 h-3.5 transition-transform ${downloadMenuOpen ? 'rotate-180' : ''}`} />
          </button>

          {downloadMenuOpen && (
            <div className="absolute right-0 mt-2 w-72 bg-white rounded-2xl border border-slate-200 shadow-xl py-2 z-40">
              <div className="px-4 py-2 border-b border-slate-100">
                <div className="text-xs font-bold text-slate-900">Download Aplikasi SiApgus</div>
                <div className="text-[11px] text-slate-500">Pilih paket aplikasi siap pakai</div>
              </div>

              <div className="py-1">
                <button
                  type="button"
                  onClick={() => triggerFileDownload('/api/download-htdocs', 'siapgus-aplikasi.zip')}
                  className="w-full px-4 py-2.5 text-left hover:bg-blue-50/80 flex items-start gap-3 transition cursor-pointer"
                >
                  <FolderDown className="w-4 h-4 text-blue-600 shrink-0 mt-0.5" />
                  <div>
                    <div className="text-xs font-semibold text-slate-800">Download Paket Aplikasi (.zip)</div>
                    <div className="text-[11px] text-slate-500">Paket lengkap siap jalankan di web server</div>
                  </div>
                </button>

                <button
                  type="button"
                  onClick={() => triggerFileDownload('/Index.html', 'Index.html')}
                  className="w-full px-4 py-2.5 text-left hover:bg-sky-50/80 flex items-start gap-3 transition cursor-pointer"
                >
                  <Globe className="w-4 h-4 text-sky-600 shrink-0 mt-0.5" />
                  <div>
                    <div className="text-xs font-semibold text-slate-800">Download Aplikasi Web (Index.html)</div>
                    <div className="text-[11px] text-slate-500">Aplikasi tunggal terintegrasi Google Spreadsheet</div>
                  </div>
                </button>

                <button
                  type="button"
                  onClick={() => triggerFileDownload('/Code.gs', 'Code.gs')}
                  className="w-full px-4 py-2.5 text-left hover:bg-emerald-50/80 flex items-start gap-3 transition cursor-pointer"
                >
                  <FileCode className="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                  <div>
                    <div className="text-xs font-semibold text-slate-800">Download Script Spreadsheet (Code.gs)</div>
                    <div className="text-[11px] text-slate-500">Backend Google Apps Script 9 tabel sheet</div>
                  </div>
                </button>

                <button
                  type="button"
                  onClick={handleDownloadJsonBackup}
                  className="w-full px-4 py-2.5 text-left hover:bg-slate-50 flex items-start gap-3 transition cursor-pointer border-t border-slate-100 mt-1 pt-2.5"
                >
                  <FileJson className="w-4 h-4 text-amber-600 shrink-0 mt-0.5" />
                  <div>
                    <div className="text-xs font-semibold text-slate-800">Download Cadangan Database (.json)</div>
                    <div className="text-[11px] text-slate-500">Salinan lengkap seluruh data aplikasi</div>
                  </div>
                </button>
              </div>
            </div>
          )}
        </div>

        {/* Spreadsheet Sync Button & Indicator */}
        <div className="flex items-center gap-1">
          <button
            type="button"
            onClick={onOpenSpreadsheetSync}
            className={`inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border transition cursor-pointer whitespace-nowrap shrink-0 ${
              hasSheetsUrl
                ? 'bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border-emerald-200'
                : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200'
            }`}
            title="Kelola Sinkronisasi Google Spreadsheet"
          >
            {isSyncing ? (
              <RefreshCw className="w-3.5 h-3.5 text-emerald-600 animate-spin shrink-0" />
            ) : hasSheetsUrl ? (
              <CheckCircle2 className="w-3.5 h-3.5 text-emerald-600 shrink-0" />
            ) : (
              <Database className="w-3.5 h-3.5 text-slate-500 shrink-0" />
            )}

            <span className="hidden md:inline">
              {isSyncing
                ? 'Menyinkronkan...'
                : hasSheetsUrl
                ? `Tersinkron Spreadsheet${lastSyncedToSheets ? ` (${formatShortTime(lastSyncedToSheets)})` : ''}`
                : 'Integrasi Spreadsheet'}
            </span>
            <span className="md:hidden">
              {isSyncing ? 'Sync...' : 'Spreadsheet'}
            </span>
          </button>

          {/* Quick sync button if URL is connected */}
          {hasSheetsUrl && onQuickSync && (
            <button
              type="button"
              onClick={onQuickSync}
              disabled={isSyncing}
              className="p-1.5 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 rounded-lg transition cursor-pointer disabled:opacity-50 shrink-0"
              title="Sinkronkan Sekarang ke Google Spreadsheet"
            >
              <RefreshCw className={`w-3.5 h-3.5 ${isSyncing ? 'animate-spin' : ''}`} />
            </button>
          )}
        </div>

        {/* User Badge */}
        <div className="flex items-center gap-2 pl-2 border-l border-slate-200">
          <div className="w-8 h-8 rounded-lg bg-blue-600 text-white font-bold text-xs flex items-center justify-center shadow-2xs overflow-hidden shrink-0">
            {currentUser.avatarUrl ? (
              <img
                src={currentUser.avatarUrl}
                alt={currentUser.fullName}
                className="w-full h-full object-cover"
              />
            ) : (
              currentUser.fullName.charAt(0).toUpperCase()
            )}
          </div>
          <div className="hidden lg:block text-left text-xs">
            <p className="font-bold text-slate-800 truncate max-w-36" title={currentUser.fullName}>
              {currentUser.fullName}
            </p>
            <p className="text-[11px] text-slate-500 capitalize">
              {currentUser.role === 'admin' ? 'Administrator' : 'Guru'}
            </p>
          </div>
        </div>

        {/* Logout Quick Button */}
        <button
          type="button"
          onClick={onLogout}
          title="Keluar Aplikasi"
          className="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition cursor-pointer shrink-0"
        >
          <LogOut className="w-4 h-4" />
        </button>
      </div>
    </header>
  );
};


