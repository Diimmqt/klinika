<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApotekerController extends Controller
{
    private array $resepList = [
        ['id' => 'RX-20261006-023', 'masuk' => '10.05', 'pasien' => 'Siti Aminah',   'rm' => 'RM-000128', 'kunjungan' => 'KJ-20261006-023', 'dokter' => 'dr. Andi Prasetyo', 'status' => 'Menunggu verifikasi'],
        ['id' => 'RX-20261006-021', 'masuk' => '09.50', 'pasien' => 'Dewi Kartika',  'rm' => 'RM-000130', 'kunjungan' => 'KJ-20261006-021', 'dokter' => 'dr. Laila Nuraini', 'status' => 'Diracik'],
        ['id' => 'RX-20261006-019', 'masuk' => '09.42', 'pasien' => 'Hendra Wijaya', 'rm' => 'RM-000135', 'kunjungan' => 'KJ-20261006-019', 'dokter' => 'dr. Andi Prasetyo', 'status' => 'Siap diserahkan'],
        ['id' => 'RX-20261006-018', 'masuk' => '09.20', 'pasien' => 'Lilis Suryani', 'rm' => 'RM-000136', 'kunjungan' => 'KJ-20261006-018', 'dokter' => 'dr. Andi Prasetyo', 'status' => 'Diserahkan'],
    ];

    private array $obatList = [
        ['kode' => 'OBT-001', 'nama' => 'Paracetamol', 'bentuk' => 'Tablet', 'kekuatan' => '500 mg',       'harga' => 2000,  'min' => 50,  'status' => 'Aktif'],
        ['kode' => 'OBT-002', 'nama' => 'Cetirizine',  'bentuk' => 'Tablet', 'kekuatan' => '10 mg',        'harga' => 5000,  'min' => 20,  'status' => 'Aktif'],
        ['kode' => 'OBT-003', 'nama' => 'Oralit',      'bentuk' => 'Sachet', 'kekuatan' => '200 mL larutan','harga' => 3000,  'min' => 30,  'status' => 'Aktif'],
        ['kode' => 'OBT-004', 'nama' => 'NaCl',        'bentuk' => 'Botol',  'kekuatan' => '0,9% • 100 mL','harga' => 15000, 'min' => 10,  'status' => 'Aktif'],
    ];

    private array $stokList = [
        ['obat' => 'Paracetamol 500 mg', 'batch' => 'PCM-2607-A', 'kadaluwarsa' => '31 Jul 2027', 'saldo' => 230, 'min' => 50,  'satuan' => 'tablet', 'status' => 'Stok cukup'],
        ['obat' => 'Cetirizine 10 mg',   'batch' => 'CTZ-2608-B', 'kadaluwarsa' => '31 Agu 2027', 'saldo' => 7,   'min' => 20,  'satuan' => 'tablet', 'status' => 'Stok rendah'],
        ['obat' => 'Oralit sachet',       'batch' => 'ORT-2606-A', 'kadaluwarsa' => '30 Jun 2027', 'saldo' => 18,  'min' => 30,  'satuan' => 'sachet', 'status' => 'Stok rendah'],
    ];

    private function authorize(): void
    {
        abort_unless(auth()->user()?->hasAnyRole(['apoteker', 'super_admin']), 403);
    }

    public function index()
    {
        $this->authorize();
        return view('apoteker.index', ['resep' => $this->resepList, 'stok' => $this->stokList]);
    }

    public function resepIndex(Request $request)
    {
        $this->authorize();
        return view('apoteker.resep.index', ['resep' => $this->resepList]);
    }

    public function resepShow(string $rx)
    {
        $this->authorize();
        return view('apoteker.resep.show', ['rx' => $rx]);
    }

    public function obatIndex(Request $request)
    {
        $this->authorize();
        $editId = $request->query('edit');
        return view('apoteker.obat.index', ['obat' => $this->obatList, 'editId' => $editId]);
    }

    public function obatStore(Request $request)
    {
        $this->authorize();
        return redirect()->route('apoteker.obat.index')->with('success', 'Data obat disimpan (simulasi).');
    }

    public function stokIndex(Request $request)
    {
        $this->authorize();
        $mutasiItem = $request->query('mutasi');
        return view('apoteker.stok.index', ['stok' => $this->stokList, 'mutasiItem' => $mutasiItem]);
    }

    public function stokStore(Request $request)
    {
        $this->authorize();
        return redirect()->route('apoteker.stok.index')->with('success', 'Penerimaan batch disimpan (simulasi).');
    }
}
