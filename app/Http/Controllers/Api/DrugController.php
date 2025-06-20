<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Drug;
use Illuminate\Http\JsonResponse;

class DrugController extends Controller
{
    public function indexStock(): JsonResponse
    {
        try {
            $drugs = Drug::select('id', 'kode_obat', 'nama_obat', 'satuan_obat', 'stock_obat')->get();

            return response()->json([
                'success' => true,
                'message' => 'Daftar stok obat',
                'data' => $drugs
            ], 200);

        } catch (Exception $e) {
            // log error untuk debug developer
            Log::error('Gagal mengambil data stok obat: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data stok obat.',
                'error'   => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }
}
