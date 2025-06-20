<?php

namespace App\Http\Controllers\Dashboard\DetailTransaction;

use App\Http\Controllers\BreadCrumb;
use App\DataTables\DetailTransactionDataTable;
use App\Http\Controllers\BaseDashboardController;

class DetailTransactionController extends BaseDashboardController
{
    public function index(DetailTransactionDataTable $dataTable)
    {
        $this->addBreadcrumb(new BreadCrumb(route('dashboard'), 'Riwayat'));
        $this->addBreadcrumb(new BreadCrumb(route('dashboard.detailtransaction.detailtransactions.index'), 'Detail Pemesanan Obat'));
        $this->addData('head', 'Detail Pemesanan Obat');
        $this->setTitle('Detail Pemesanan Obat');
        return $this->renderDatatable($dataTable, 'dashboard.detailtransaction.detailtransactions.index');
    }

}
