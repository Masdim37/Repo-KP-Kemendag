<?php

namespace App\Http\Controllers;

use App\Services\PenelitianWorkspaceService;
use App\Services\PenelitianResearchService;
use App\Services\PenelitianChpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class penelitianController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('penelitian as p')
            ->leftJoin('users as u', 'u.userID', '=', 'p.created_by')
            ->select(
                'p.penelitianID',
                'p.nama_penelitian',
                'p.status',
                'p.kode_satker',
                'p.nama_satker',
                'p.tahun_anggaran',
                'p.tanggal_penelitian',
                'p.total_anggaran',
                'p.created_by',
                'p.created_at',
                'p.updated_at',
                'u.name as creator_name'
            );

        $search = trim((string) $request->query('q', ''));
        $status = strtoupper(trim((string) $request->query('status', '')));
        $kodeSatker = trim((string) $request->query('kode_satker', ''));

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('p.nama_penelitian', 'like', "%{$search}%")
                    ->orWhere('p.nama_satker', 'like', "%{$search}%")
                    ->orWhere('u.name', 'like', "%{$search}%");
            });
        }

        if (in_array($status, ['DRAFT', 'FINAL'], true)) {
            $query->where('p.status', $status);
        }

        if ($kodeSatker !== '') {
            $query->where('p.kode_satker', $kodeSatker);
        }

        $penelitian = $query
            ->orderByDesc('p.updated_at')
            ->orderByDesc('p.penelitianID')
            ->paginate(15)
            ->withQueryString();

        $satkerOptions = DB::table('satker')
            ->select('kode_satker', 'nama_satker')
            ->orderBy('nama_satker')
            ->get();

        $summary = [
            'total' => DB::table('penelitian')->count(),
            'draft' => DB::table('penelitian')->where('status', 'DRAFT')->count(),
            'final' => DB::table('penelitian')->where('status', 'FINAL')->count(),
        ];

        return view('menu.penelitian.index', compact(
            'penelitian',
            'satkerOptions',
            'summary',
            'search',
            'status',
            'kodeSatker'
        ));
    }

    public function create(PenelitianWorkspaceService $workspaceService)
    {
        $references = $workspaceService->organizationReferences();

        return view('menu.penelitian.form', [
            'mode' => 'create',
            'penelitian' => null,
            'unitEselon1' => $references['unitEselon1'],
            'unitEselon2' => $references['unitEselon2'],
            'satker' => $references['satker'],
            'selectedDocuments' => [
                'RENJA' => null,
                'RKBMN' => null,
                'JUMLAH_PEGAWAI' => null,
                'RKA' => null,
                'TOR' => [],
                'RAB' => [],
            ],
            'selectedDocumentDetails' => collect(),
            'parties' => collect(),
            'hasResearchResults' => false,
            'hasilBagianA' => collect(),
            'hasilBagianB' => collect(),
            'hasilBagianC' => collect(),
            'hasilBagianD' => collect(),
            'hasilBagianD1' => collect(),
            'hasilBagianD2' => collect(),
            'hasilBagianE' => collect(),
            'hasilBagianF' => collect(),
            'finalizationReadiness' => [
                'all_ready' => false,
                'checks' => [],
            ],
        ]);
    }

    public function store(
        Request $request,
        PenelitianWorkspaceService $workspaceService
    ) {
        $validated = $this->validateWorkspace($request);

        try {
            $penelitianID = $workspaceService->createWorkspace(
                $validated,
                $request->user()
            );

            return redirect()
                ->route('penelitian.index')
                ->with('success', 'Workspace penelitian berhasil dibuat dan disimpan sebagai DRAFT.');
        } catch (\RuntimeException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            Log::error('GAGAL MEMBUAT WORKSPACE PENELITIAN', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Workspace penelitian gagal dibuat karena terjadi kesalahan internal server.');
        }
    }

    public function edit(
        int $penelitianID,
        PenelitianWorkspaceService $workspaceService,
        PenelitianResearchService $researchService,
        PenelitianChpService $chpService
    ) {
        $penelitian = DB::table('penelitian')
            ->where('penelitianID', $penelitianID)
            ->first();

        if (!$penelitian) {
            abort(404);
        }

        if ($penelitian->status !== 'DRAFT') {
            return redirect()
                ->route('penelitian.index')
                ->with('error', 'Penelitian sudah FINAL. Workspace FINAL tidak dapat diedit.');
        }

        $references = $workspaceService->organizationReferences();

        return view('menu.penelitian.form', [
            'mode' => 'edit',
            'penelitian' => $penelitian,
            'unitEselon1' => $references['unitEselon1'],
            'unitEselon2' => $references['unitEselon2'],
            'satker' => $references['satker'],
            'selectedDocuments' => $workspaceService->currentSelection($penelitianID),
            'selectedDocumentDetails' => $workspaceService->selectedDocumentDetails($penelitianID),
            'parties' => $workspaceService->parties($penelitianID),
            'hasResearchResults' => $workspaceService->hasGeneratedResults($penelitianID),
            'hasilBagianA' => $researchService->partAResults($penelitianID),
            'hasilBagianB' => $researchService->partBResults($penelitianID),
            'hasilBagianC' => $researchService->partCResults($penelitianID),
            'hasilBagianD' => $researchService->partDResults($penelitianID),
            'hasilBagianD1' => $researchService->partD1Results($penelitianID),
            'hasilBagianD2' => $researchService->partD2Results($penelitianID),
            'hasilBagianE' => $researchService->partEResults($penelitianID),
            'hasilBagianF' => $researchService->partFResults($penelitianID),
            'finalizationReadiness' => $chpService->readiness($penelitianID),
        ]);
    }

    public function update(
        Request $request,
        int $penelitianID,
        PenelitianWorkspaceService $workspaceService,
        PenelitianResearchService $researchService,
        PenelitianChpService $chpService
    ) {
        $validated = $this->validateWorkspace($request);

        try {
            $result = $workspaceService->updateWorkspace(
                $penelitianID,
                $validated,
                $request->user()
            );

            // Jika sumber tidak berubah, simpan override STATUS/PENJELASAN Bagian A.
            // Jika sumber berubah, WorkspaceService sudah menginvalidasi hasil lama dan
            // input hasil A dari browser tidak boleh menghidupkan kembali hasil stale.
            if (!$result['source_changed'] && !empty($validated['hasil_a'] ?? [])) {
                $researchService->savePartAOverrides(
                    $penelitianID,
                    $validated['hasil_a'],
                    $request->user()
                );
            }


            if (!$result['source_changed'] && !empty($validated['hasil_b'] ?? [])) {
                $researchService->savePartBOverrides(
                    $penelitianID,
                    $validated['hasil_b'],
                    $request->user()
                );
            }

            // Bagian C: PAGU RENJA, PAGU RKA, dan CATATAN dapat di-override user.
            // SELISIH tidak dikirim/disimpan sebagai override dan selalu dihitung otomatis.
            if (!$result['source_changed'] && !empty($validated['hasil_c'] ?? [])) {
                $researchService->savePartCOverrides(
                    $penelitianID,
                    $validated['hasil_c'],
                    $request->user()
                );
            }

            // Bagian D: PAGU RENJA, PAGU RKA, dan PENJELASAN dapat di-override user.
            // SELISIH selalu otomatis dan tidak mempunyai input manual.
            if (!$result['source_changed'] && !empty($validated['hasil_d'] ?? [])) {
                $researchService->savePartDOverrides(
                    $penelitianID,
                    $validated['hasil_d'],
                    $request->user()
                );
            }

            // Bagian D.1: hanya PENJELASAN yang dapat di-override user.
            // PAGU RENJA, PAGU RKA, dan SELISIH tetap read-only hasil sistem.
            if (!$result['source_changed'] && !empty($validated['hasil_d1'] ?? [])) {
                $researchService->savePartD1Overrides(
                    $penelitianID,
                    $validated['hasil_d1'],
                    $request->user()
                );
            }

            // Bagian D.2: hanya PENJELASAN dapat di-override user.
            if (!$result['source_changed'] && !empty($validated['hasil_d2'] ?? [])) {
                $researchService->savePartD2Overrides(
                    $penelitianID,
                    $validated['hasil_d2'],
                    $request->user()
                );
            }

            // Bagian E: STATUS dan PENJELASAN dapat di-override user.
            if (!$result['source_changed'] && !empty($validated['hasil_e'] ?? [])) {
                $researchService->savePartEOverrides(
                    $penelitianID,
                    $validated['hasil_e'],
                    $request->user()
                );
            }

            // Bagian F: system notes dapat di-override/hide, USER notes
            // dapat ditambah/edit/hapus selama DRAFT.
            if (
                !empty($validated['hasil_f_system'] ?? [])
                || array_key_exists('hasil_f_user', $validated)
            ) {
                $researchService->savePartFChanges(
                    $penelitianID,
                    $validated['hasil_f_system'] ?? [],
                    $validated['hasil_f_user'] ?? [],
                    $request->user()
                );
            }

            $finalizeRequested =
                (string) $request->input('workspace_action', 'save')
                === 'finalize';

            if ($finalizeRequested) {
                if ($result['source_changed']) {
                    throw new \RuntimeException(
                        'Dokumen sumber/ruang lingkup berubah saat penyimpanan. Hasil penelitian telah diinvalidasi. Jalankan kembali Bagian A-F sebelum finalisasi CHP.'
                    );
                }

                $chpService->finalize(
                    $penelitianID,
                    $request->user()
                );

                return redirect()
                    ->route('penelitian.chp', $penelitianID)
                    ->with(
                        'success',
                        'CHP berhasil difinalisasi. Seluruh data sekarang read-only dan siap dicetak.'
                    );
            }

            $message = 'DRAFT penelitian berhasil disimpan.';

            if ($result['results_invalidated']) {
                $message .= ' Dokumen sumber berubah sehingga hasil penelitian sistem telah dihapus dan wajib dijalankan kembali.';
            } elseif ($result['source_changed']) {
                $message .= ' Perubahan dokumen sumber telah disimpan.';
            }

            return redirect()
                ->route('penelitian.index')
                ->with('success', $message);
        } catch (\RuntimeException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            Log::error('GAGAL MENYIMPAN DRAFT PENELITIAN', [
                'penelitianID' => $penelitianID,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'DRAFT penelitian gagal disimpan karena terjadi kesalahan internal server.');
        }
    }

    public function runPartA(
        Request $request,
        int $penelitianID,
        PenelitianResearchService $researchService
    ) {
        try {
            $result = $researchService->runPartA(
                $penelitianID,
                $request->user()
            );

            return response()->json([
                'success' => true,
                'title' => 'Bagian A Berhasil Diproses',
                'message' => 'Pemeriksaan Bagian A selesai dan hasilnya telah disimpan pada DRAFT penelitian.',
                'result' => $result,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'title' => 'Bagian A Gagal Diproses',
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('GAGAL MENJALANKAN PENELITIAN BAGIAN A', [
                'penelitianID' => $penelitianID,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            report($e);

            return response()->json([
                'success' => false,
                'title' => 'Bagian A Gagal Diproses',
                'message' => 'Terjadi kesalahan internal saat menjalankan penelitian Bagian A.',
            ], 500);
        }
    }

    public function runPartB(
        Request $request,
        int $penelitianID,
        PenelitianResearchService $researchService
    ) {
        try {
            $result = $researchService->runPartB(
                $penelitianID,
                $request->user()
            );

            return response()->json([
                'success' => true,
                'title' => 'Bagian B Berhasil Diproses',
                'message' => 'Pemeriksaan Bagian B selesai dan hasilnya telah disimpan pada DRAFT penelitian.',
                'result' => $result,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'title' => 'Bagian B Gagal Diproses',
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('GAGAL MENJALANKAN PENELITIAN BAGIAN B', [
                'penelitianID' => $penelitianID,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            report($e);

            return response()->json([
                'success' => false,
                'title' => 'Bagian B Gagal Diproses',
                'message' => 'Terjadi kesalahan internal saat menjalankan penelitian Bagian B.',
            ], 500);
        }
    }


    public function runPartC(
        Request $request,
        int $penelitianID,
        PenelitianResearchService $researchService
    ) {
        try {
            $result = $researchService->runPartC(
                $penelitianID,
                $request->user()
            );

            return response()->json([
                'success' => true,
                'title' => 'Bagian C Berhasil Diproses',
                'message' => 'Pemeriksaan Bagian C selesai dan hasilnya telah disimpan pada DRAFT penelitian.',
                'result' => $result,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'title' => 'Bagian C Gagal Diproses',
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('GAGAL MENJALANKAN PENELITIAN BAGIAN C', [
                'penelitianID' => $penelitianID,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            report($e);

            return response()->json([
                'success' => false,
                'title' => 'Bagian C Gagal Diproses',
                'message' => 'Terjadi kesalahan internal saat menjalankan penelitian Bagian C.',
            ], 500);
        }
    }


    public function runPartD(
        Request $request,
        int $penelitianID,
        PenelitianResearchService $researchService
    ) {
        try {
            $result = $researchService->runPartD(
                $penelitianID,
                $request->user()
            );

            return response()->json([
                'success' => true,
                'title' => 'Bagian D Berhasil Diproses',
                'message' => 'Bagian D Budget Tagging berhasil disiapkan sesuai format CHP dan telah disimpan pada DRAFT penelitian.',
                'result' => $result,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'title' => 'Bagian D Gagal Diproses',
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('GAGAL MENJALANKAN PENELITIAN BAGIAN D', [
                'penelitianID' => $penelitianID,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            report($e);

            return response()->json([
                'success' => false,
                'title' => 'Bagian D Gagal Diproses',
                'message' => 'Terjadi kesalahan internal saat menjalankan penelitian Bagian D.',
            ], 500);
        }
    }


    public function runPartD1(
        Request $request,
        int $penelitianID,
        PenelitianResearchService $researchService
    ) {
        try {
            $result = $researchService->runPartD1(
                $penelitianID,
                $request->user()
            );

            return response()->json([
                'success' => true,
                'title' => 'Bagian D.1 Berhasil Diproses',
                'message' => 'Identifikasi KRO Belanja Bidang TIK selesai dan hasilnya telah disimpan pada DRAFT penelitian.',
                'result' => $result,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'title' => 'Bagian D.1 Gagal Diproses',
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('GAGAL MENJALANKAN PENELITIAN BAGIAN D.1', [
                'penelitianID' => $penelitianID,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            report($e);

            return response()->json([
                'success' => false,
                'title' => 'Bagian D.1 Gagal Diproses',
                'message' => 'Terjadi kesalahan internal saat menjalankan penelitian Bagian D.1.',
            ], 500);
        }
    }


    public function runPartD2(
        Request $request,
        int $penelitianID,
        PenelitianResearchService $researchService
    ) {
        try {
            $result = $researchService->runPartD2(
                $penelitianID,
                $request->user()
            );

            return response()->json([
                'success' => true,
                'title' => 'Bagian D.2 Berhasil Diproses',
                'message' => 'Identifikasi Aset Bidang TIK selesai dan hasilnya telah disimpan pada DRAFT penelitian.',
                'result' => $result,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'title' => 'Bagian D.2 Gagal Diproses',
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('GAGAL MENJALANKAN PENELITIAN BAGIAN D.2', [
                'penelitianID' => $penelitianID,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            report($e);

            return response()->json([
                'success' => false,
                'title' => 'Bagian D.2 Gagal Diproses',
                'message' => 'Terjadi kesalahan internal saat menjalankan penelitian Bagian D.2.',
            ], 500);
        }
    }


    public function runPartE(
        Request $request,
        int $penelitianID,
        PenelitianResearchService $researchService
    ) {
        try {
            $result = $researchService->runPartE(
                $penelitianID,
                $request->user()
            );

            return response()->json([
                'success' => true,
                'title' => 'Bagian E Berhasil Diproses',
                'message' => 'Pemeriksaan kelengkapan dokumen pendukung selesai dan hasilnya telah disimpan pada DRAFT penelitian.',
                'result' => $result,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'title' => 'Bagian E Gagal Diproses',
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('GAGAL MENJALANKAN PENELITIAN BAGIAN E', [
                'penelitianID' => $penelitianID,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            report($e);

            return response()->json([
                'success' => false,
                'title' => 'Bagian E Gagal Diproses',
                'message' => 'Terjadi kesalahan internal saat menjalankan penelitian Bagian E.',
            ], 500);
        }
    }


    public function runPartF(
        Request $request,
        int $penelitianID,
        PenelitianResearchService $researchService
    ) {
        try {
            $result = $researchService->runPartF(
                $penelitianID,
                $request->user()
            );

            return response()->json([
                'success' => true,
                'title' => 'Bagian F Berhasil Diproses',
                'message' => 'Catatan Lain-Lain berhasil disusun dari hasil A-E, validasi jumlah pegawai, dan double-check RKA terhadap RAB.',
                'result' => $result,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'title' => 'Bagian F Gagal Diproses',
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('GAGAL MENJALANKAN PENELITIAN BAGIAN F', [
                'penelitianID' => $penelitianID,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            report($e);

            return response()->json([
                'success' => false,
                'title' => 'Bagian F Gagal Diproses',
                'message' => 'Terjadi kesalahan internal saat menjalankan penelitian Bagian F.',
            ], 500);
        }
    }

    public function showChp(
        int $penelitianID,
        PenelitianChpService $chpService
    ) {
        try {
            $snapshot = $chpService->snapshot($penelitianID);

            if ($snapshot['penelitian']->status !== 'FINAL') {
                return redirect()
                    ->route('penelitian.edit', $penelitianID)
                    ->with(
                        'error',
                        'CHP masih DRAFT. Finalisasi terlebih dahulu sebelum membuka tampilan FINAL.'
                    );
            }

            return view('menu.penelitian.chp', [
                'snapshot' => $snapshot,
                'printMode' => false,
            ]);
        } catch (\RuntimeException $e) {
            return redirect()
                ->route('penelitian.index')
                ->with('error', $e->getMessage());
        }
    }

    public function printChp(
        int $penelitianID,
        PenelitianChpService $chpService
    ) {
        try {
            $snapshot = $chpService->snapshot($penelitianID);

            if ($snapshot['penelitian']->status !== 'FINAL') {
                return redirect()
                    ->route('penelitian.edit', $penelitianID)
                    ->with(
                        'error',
                        'CHP hanya dapat dicetak setelah status FINAL.'
                    );
            }

            return view('menu.penelitian.chp', [
                'snapshot' => $snapshot,
                'printMode' => true,
            ]);
        } catch (\RuntimeException $e) {
            return redirect()
                ->route('penelitian.index')
                ->with('error', $e->getMessage());
        }
    }

    public function logPrintChp(
        Request $request,
        int $penelitianID,
        PenelitianChpService $chpService
    ) {
        try {
            $chpService->recordPrint(
                $penelitianID,
                $request->user()
            );

            return response()->json([
                'success' => true,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function documentOptions(
        Request $request,
        PenelitianWorkspaceService $workspaceService
    ) {
        $validated = $request->validate([
            'kode_unit_eselon1' => 'required|string|max:50',
            'kode_unit_eselon2' => 'required|string|max:50',
            'kode_satker' => 'required|string|max:50',
        ]);

        try {
            $organization = $workspaceService->resolveOrganization(
                $validated['kode_unit_eselon1'],
                $validated['kode_unit_eselon2'],
                $validated['kode_satker']
            );

            return response()->json([
                'success' => true,
                'organization' => $organization,
                'documents' => $workspaceService->documentOptions($organization),
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    private function validateWorkspace(Request $request): array
    {
        return $request->validate([
            'nama_penelitian' => 'required|string|max:255',
            'kode_unit_eselon1' => 'required|string|max:50',
            'kode_unit_eselon2' => 'required|string|max:50',
            'kode_satker' => 'required|string|max:50',

            // RKA wajib karena merupakan object utama penelitian dan menjadi
            // sumber tahun anggaran, total pagu, serta Program-Kegiatan header.
            'rka_document_id' => 'required|string|max:8',

            // Dokumen lain boleh belum dipilih selama workspace masih DRAFT.
            'renja_document_id' => 'nullable|string|max:8',
            'rkbmn_document_id' => 'nullable|string|max:8',
            'jumlah_pegawai_document_id' => 'nullable|string|max:8',

            'tor_document_ids' => 'nullable|array',
            'tor_document_ids.*' => 'string|max:8|distinct',
            'rab_document_ids' => 'nullable|array',
            'rab_document_ids.*' => 'string|max:8|distinct',

            'peneliti_2' => 'nullable|string|max:255',
            'peneliti_3' => 'nullable|string|max:255',
            'perwakilan_1' => 'nullable|string|max:255',
            'perwakilan_2' => 'nullable|string|max:255',
            'perwakilan_3' => 'nullable|string|max:255',
            'workspace_action' => [
                'nullable',
                Rule::in(['save', 'finalize']),
            ],

            // Override hasil Bagian A hanya tersimpan jika hasil sistem sudah ada.
            'hasil_a' => 'nullable|array',
            'hasil_a.*.status' => ['nullable', Rule::in([
                'SESUAI',
                'TIDAK_SESUAI',
                'PERLU_KONFIRMASI',
            ])],
            'hasil_a.*.penjelasan' => 'nullable|string|max:65000',


            // Override hasil Bagian B hanya tersimpan jika hasil sistem sudah ada.
            'hasil_b' => 'nullable|array',
            'hasil_b.*.status' => ['nullable', Rule::in([
                'SESUAI',
                'TIDAK_SESUAI',
                'PERLU_KONFIRMASI',
            ])],
            'hasil_b.*.penjelasan' => 'nullable|string|max:65000',


            // Override hasil Bagian C hanya tersimpan jika baris hasil sistem sudah ada.
            // Selisih sengaja tidak mempunyai field input/validasi manual.
            'hasil_c' => 'nullable|array',
            'hasil_c.*.pagu_renja' => 'nullable|integer|min:0|max:999999999999999999',
            'hasil_c.*.pagu_rka' => 'nullable|integer|min:0|max:999999999999999999',
            'hasil_c.*.catatan' => 'nullable|string|max:65000',


            // Override Bagian D. Selisih tidak memiliki input user.
            'hasil_d' => 'nullable|array',
            'hasil_d.*.pagu_renja' => 'nullable|integer|min:0|max:999999999999999999',
            'hasil_d.*.pagu_rka' => 'nullable|integer|min:0|max:999999999999999999',
            'hasil_d.*.penjelasan' => 'nullable|string|max:65000',


            // D.1 hanya mengizinkan override PENJELASAN.
            'hasil_d1' => 'nullable|array',
            'hasil_d1.*.penjelasan' => 'nullable|string|max:65000',


            // D.2 hanya mengizinkan override PENJELASAN.
            'hasil_d2' => 'nullable|array',
            'hasil_d2.*.penjelasan' => 'nullable|string|max:65000',


            // Bagian E: domain status khusus kelengkapan dokumen.
            'hasil_e' => 'nullable|array',
            'hasil_e.*.status' => ['nullable', Rule::in([
                'LENGKAP',
                'BELUM_LENGKAP',
                'PERLU_KONFIRMASI',
            ])],
            'hasil_e.*.penjelasan' => 'nullable|string|max:65000',


            // Bagian F - system note override/hide.
            'hasil_f_system' => 'nullable|array',
            'hasil_f_system.*.catatan' => 'nullable|string|max:65000',
            'hasil_f_system.*.dihapus' => 'nullable|boolean',

            // Bagian F - catatan manual user.
            'hasil_f_user' => 'nullable|array',
            'hasil_f_user.*.catatan_id' => 'nullable|integer|min:1',
            'hasil_f_user.*.catatan' => 'nullable|string|max:65000',
        ], [
            'nama_penelitian.required' => 'Nama penelitian wajib diisi.',
            'kode_unit_eselon1.required' => 'Unit Eselon I wajib dipilih.',
            'kode_unit_eselon2.required' => 'Unit Eselon II wajib dipilih.',
            'kode_satker.required' => 'Satker wajib dipilih.',
            'rka_document_id.required' => 'Dokumen RKA wajib dipilih.',
        ]);
    }
}
