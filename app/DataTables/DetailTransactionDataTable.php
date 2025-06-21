<?php

namespace App\DataTables;

use App\Models\DetailTransaction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DetailTransactionDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('drug_name', function ($detail) {
                return optional($detail->drug)->nama_obat ?? '-';
            })
            ->addColumn('transaction_id', function ($detail) {
                return $detail->transaction_id ?? '-';
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp ' . number_format($detail->drug->harga_obat, 0, ',', '.');
            })
            ->editColumn('price', function ($detail) {
                return 'Rp ' . number_format($detail->price, 0, ',', '.');
            })
            ->editColumn('created_at', function ($detail) {
                return $detail->created_at
                    ->timezone('Asia/Jakarta')
                    ->translatedFormat('d F Y, H:i');
            })
            ->editColumn('updated_at', function ($detail) {
                return $detail->updated_at
                    ->timezone('Asia/Jakarta')
                    ->translatedFormat('d F Y, H:i');
            })
            ->setRowId('id');
    }

    /**
     * Query source of dataTable.
     */
    public function query(DetailTransaction $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['drug', 'transaction'])
            ->select(['id', 'drug_id', 'transaction_id', 'qty', 'price', 'created_at', 'updated_at']);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('detail-transaction-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('dashboard.detailtransaction.detailtransactions.index')) // sesuaikan route ini
            ->parameters([
                'searching' => true,
                'responsive' => true,
                'autoWidth' => false,
                'order' => [[0, 'desc']],
            ])
            ->initComplete('function(settings, json) {
                var table = window.LaravelDataTables["detail-transaction-table"];
                $("#input-search").on("keyup", function() {
                    table.search($(this).val()).draw();
                });
            }')
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    /**
     * DataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('transaction_id')->title('Kode Transaksi'),
            Column::make('drug_name')->title('Nama Obat'),
            Column::make('qty')->title('Jumlah'),
            Column::make('subtotal')->title('Harga'),
            Column::make('price')->title('Subtotal'),
            Column::make('created_at')->title('Dibuat Pada'),
            Column::make('updated_at')->title('Diedit Pada'),
        ];
    }

    /**
     * Export filename.
     */
    protected function filename(): string
    {
        return 'DetailTransaction_' . date('YmdHis');
    }
}
