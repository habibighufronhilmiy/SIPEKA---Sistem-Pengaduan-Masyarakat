<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengaduan::with(['user', 'kategori', 'petugas']);

        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $pengaduans = $query->latest()->get();
        $kategoris = \App\Models\Kategori::all();

        if ($request->has('export')) {
            return $this->export($pengaduans, $request->export);
        }

        return view('admin.laporan.index', compact('pengaduans', 'kategoris'));
    }

    public function export($pengaduans, $format)
    {
        AuditLog::log('Export laporan', 'Mengexport laporan pengaduan format ' . strtoupper($format) . ' (' . $pengaduans->count() . ' data)', null, 'pengaduan');

        if ($format === 'pdf') {
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('admin.laporan.pdf', compact('pengaduans'));
            return $pdf->download('laporan-pengaduan-' . date('Y-m-d') . '.pdf');
        }

        if ($format === 'excel') {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="laporan-pengaduan-' . date('Y-m-d') . '.xls"');

            echo '<table border="1">';
            echo '<tr><th>No</th><th>Judul</th><th>Pelapor</th><th>Kategori</th><th>Status</th><th>Tanggal</th></tr>';
            foreach ($pengaduans as $i => $p) {
                echo '<tr>';
                echo '<td>' . ($i + 1) . '</td>';
                echo '<td>' . $p->judul . '</td>';
                echo '<td>' . $p->user->name . '</td>';
                echo '<td>' . $p->kategori->nama_kategori . '</td>';
                echo '<td>' . $p->status . '</td>';
                echo '<td>' . $p->created_at->format('d/m/Y') . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            exit;
        }

        return back()->with('error', 'Format tidak didukung.');
    }
}
