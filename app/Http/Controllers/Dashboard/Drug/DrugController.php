<?php

namespace App\Http\Controllers\Dashboard\Drug;

use App\DataTables\DrugDataTable;
use App\Http\Controllers\BaseDashboardController;
use App\Http\Controllers\BreadCrumb;
use App\Http\Requests\Dashboard\Drug\StoreDrugRequest;
use App\Http\Requests\Dashboard\Drug\UpdateDrugRequest;
use App\Http\Resources\DefaultResource;
use App\Models\Drug;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class DrugController extends BaseDashboardController
{
    function index(DrugDataTable $dataTable, Request $request)
    {
        $this->addBreadcrumb(new BreadCrumb(route('dashboard'), 'Data Master'));
        $this->addBreadcrumb(new BreadCrumb(route('dashboard.drug.drugs.index'), 'Obat'));
        $this->addData('head', 'Obat');
        $this->setTitle('Obat');
        // $this->setLayout(Session::get('layout', 'layouts.dashboard.vertical'));
        return $this->renderDatatable($dataTable, 'dashboard.drug.drungs.index');
    }

    function store(StoreDrugRequest $request) {

        $data = $request->validated();

        // Tambahkan user_id dari user yang sedang login
        $data['user_id'] = auth()->id();

        try {
            $item = Drug::create($data);
    
            if ($request->expectsJson()) {
                return new DefaultResource(true, 'Obat berhasil ditambahkan', $item);
            }
    
            return redirect()->route('dashboard.drug.drugs.index');
        } catch (\Throwable $e) {
            if ($request->expectsJson()) {
                return new DefaultResource(false, $e->getMessage(), []);
            }
            abort(500, $e->getMessage());
        }
    }

    function update(UpdateDrugRequest $request, Drug $drug)
    {
        $data = $request->validated();

        try {

            $drug->update($data);

            if ($request->expectsJson()) {
                return new DefaultResource(true, 'Obat berhasil diubah', $drug);
            }

            return redirect()->route('dashboard.drug.drungs.index');
            
        } catch (\Throwable $e) {
            if ($request->expectsJson()) {
                return new DefaultResource(false, $e->getMessage(), []);
            }
            abort(500, $e->getMessage());
        }
    }

    function destroy(Request $request, Drug $drug)
    {
        try {
            $drug->delete();

            if ($request->expectsJson()) {
                return new DefaultResource(true, 'Driver berhasil dihapus', $drug);
            }

            return redirect()->route('dashboard.drug.drungs.index');
        } catch (\Throwable $e) {
            if ($request->expectsJson()) {
                return new DefaultResource(false, $e->getMessage(), []);
            }
            abort(500, $e->getMessage());
        }
    }
}
