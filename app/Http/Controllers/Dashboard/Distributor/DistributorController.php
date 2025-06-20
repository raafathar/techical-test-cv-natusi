<?php

namespace App\Http\Controllers\Dashboard\Distributor;

use App\Models\Driver;
use App\Models\Distributor;
use Illuminate\Http\Request;
use App\Http\Controllers\BreadCrumb;
use App\Http\Resources\DefaultResource;
use Illuminate\Support\Facades\Session;
use App\DataTables\DistributorDataTable;
use App\Http\Controllers\BaseDashboardController;
use App\Http\Requests\Dashboard\Distributor\StoreDistributorRequest;
use App\Http\Requests\Dashboard\Distributor\UpdateDistributorRequest;

class DistributorController extends BaseDashboardController
{
    function index(DistributorDataTable $dataTable, Request $request)
    {
        $this->addBreadcrumb(new BreadCrumb(route('dashboard'), 'Data Master'));
        $this->addBreadcrumb(new BreadCrumb(route('dashboard.distributor.distributors.index'), 'distributor'));
        $this->addData('head', 'Distributor');
        $this->setTitle('Distributor');
        $this->setLayout(Session::get('layout', 'layouts.dashboard.vertical'));
        return $this->renderDatatable($dataTable, 'dashboard.distributor.distributors.index');
    }

    function store(StoreDistributorRequest $request) {
        $data = $request->validated();

        try {
            $item = Distributor::create($data);
    
            if ($request->expectsJson()) {
                return new DefaultResource(true, 'Distributor berhasil ditambahkan', $item);
            }
    
            return redirect()->route('dashboard.distributor.distributors.index');
        } catch (\Throwable $e) {
            if ($request->expectsJson()) {
                return new DefaultResource(false, $e->getMessage(), []);
            }
            abort(500, $e->getMessage());
        }
    }

    function update(UpdateDistributorRequest $request, Distributor $distributor)
    {
        $data = $request->validated();

        try {
            $distributor->update($data);

            if ($request->expectsJson()) {
                return new DefaultResource(true, 'Distributor berhasil diubah', $distributor);
            }

            return redirect()->route('dashboard.distributor.distributors.index');
        } catch (\Throwable $e) {
            if ($request->expectsJson()) {
                return new DefaultResource(false, $e->getMessage(), []);
            }
            abort(500, $e->getMessage());
        }
    }

    function destroy(Request $request, Distributor $distributor)
    {
        try {
            $distributor->delete();

            if ($request->expectsJson()) {
                return new DefaultResource(true, 'Distributor berhasil dihapus', $distributor);
            }

            return redirect()->route('dashboard.distributor.distributors.index');
        } catch (\Throwable $e) {
            if ($request->expectsJson()) {
                return new DefaultResource(false, $e->getMessage(), []);
            }
            abort(500, $e->getMessage());
        }
    }
}
