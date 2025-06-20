<?php

namespace App\Http\Controllers\Dashboard\Transaction;

use App\Models\Drug;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DetailTransaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BreadCrumb;
use Illuminate\Support\Facades\View;
use App\Http\Resources\DefaultResource;
use Illuminate\Support\Facades\Session;
use App\DataTables\TransactionDataTable;
use App\Http\Controllers\BaseDashboardController;
use App\Http\Requests\Dashboard\Drug\StoreDrugRequest;
use App\Http\Requests\Dashboard\Drug\UpdateDrugRequest;

class TransactionController extends BaseDashboardController
{
    public function index(TransactionDataTable $dataTable)
    {
        $this->addBreadcrumb(new BreadCrumb(route('dashboard'), 'Data Master'));
        $this->addBreadcrumb(new BreadCrumb(route('dashboard.transaction.transactions.index'), 'Pemesanan Obat'));
        $this->addData('head', 'Pemesanan Obat');
        $this->setTitle('Pemesanan Obat');

        View::share('drugs', Drug::where('stock_obat', '>', 0)->get());

        return $this->renderDatatable($dataTable, 'dashboard.transaction.transactions.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'drugs' => 'required|array|min:1',
            'drugs.*.drug_id' => 'required|exists:drugs,id',
            'drugs.*.qty' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($request) {
                    preg_match('/drugs\.(\d+)\.qty/', $attribute, $matches);
                    $index = $matches[1] ?? null;

                    if ($index !== null) {
                        $drugId = $request->input("drugs.$index.drug_id");
                        $drug = \App\Models\Drug::find($drugId);

                        if ($drug && $value > $drug->stock) {
                            $fail("Stok obat '{$drug->nama_obat}' tidak mencukupi. Tersedia: {$drug->stock_obat}.");
                        }
                    }
                }
            ],
        ]);

        DB::beginTransaction();

        try {
            // 1. Buat transaksi
            $transaction = Transaction::create([
                'id' => Str::uuid(),
                'user_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Loop untuk setiap detail obat
            foreach ($request->drugs as $item) {

                $drug = Drug::findOrFail($item['drug_id']);

                $qty = $item['qty'];
                $price = $drug->harga_obat * $qty;

                DetailTransaction::create([
                    'id' => Str::uuid(),
                    'transaction_id' => $transaction->id,
                    'drug_id' => $drug->id,
                    'qty' => $qty,
                    'price' => $price,
                ]);

                $drug->decrement('stock_obat', $qty);
            }
            
            DB::commit();

            if ($request->expectsJson()) {
                return new DefaultResource(true, 'Transaksi berhasil ditambahkan', $transaction->load('details'));
            }

            return redirect()->route('dashboard.transaction.transactions.index')
                            ->with('success', 'Transaksi berhasil ditambahkan');
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return new DefaultResource(false, $e->getMessage(), []);
            }

            abort(500, $e->getMessage());
        }
    }

    public function show($id)
    {
        $transaction = Transaction::with(['details.drug'])->findOrFail($id);

        $details = $transaction->details->map(function ($item) {
            return [
                'nama_obat'  => $item->drug->nama_obat,
                'kode_obat'  => $item->drug->kode_obat,
                'qty'        => $item->qty,
                'price'      => $item->price,
            ];
        });

        return response()->json([
            'success' => true,
            'details' => $details,
        ]);
    }

    public function print($id)
    {
        $transaction = Transaction::with(['details.drug', 'user'])->findOrFail($id);
        $pdf = Pdf::loadView('dashboard.transaction.transactions.print', compact('transaction'));
        return $pdf->stream('transaksi-'.$transaction->id.'.pdf');
    }


}
