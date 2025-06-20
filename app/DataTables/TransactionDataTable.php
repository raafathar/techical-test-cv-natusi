<?php

namespace App\DataTables;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TransactionDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('user_name', function (Transaction $transaction) {
                return optional($transaction->user)->name ?? '-';
            })
            ->addColumn('total_transaction', function (Transaction $transaction) {
                $total = $transaction->details->sum(function ($detail) {
                    return $detail->qty * $detail->price;
                });

                return 'Rp ' . number_format($total, 0, ',', '.');
            })
            ->addColumn('action', function (Transaction $transaction) {
                return view('dashboard.transaction.transactions.action', compact('transaction'));
            })
            ->editColumn('created_at', function (Transaction $transaction) {
                return $transaction->created_at
                    ->timezone('Asia/Jakarta')
                    ->translatedFormat('d F Y, H:i');
            })
            ->editColumn('updated_at', function (Transaction $transaction) {
                return $transaction->updated_at
                    ->timezone('Asia/Jakarta')
                    ->translatedFormat('d F Y, H:i');
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Transaction $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['user', 'details']) // relasi user dan details
            ->select(['id', 'user_id', 'created_at', 'updated_at']);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('transaction-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('dashboard.transaction.transactions.index'))
            ->parameters([
                'searching' => false,
            ])
            ->initComplete('function(settings, json) {
                var table = window.LaravelDataTables["transaction-table"];
                $("#input-search").on("keyup", function() {
                    var searchTerm = $(this).val().toLowerCase();
                    table.rows().every(function() {
                        var row = this.node();
                        var rowText = row.textContent.toLowerCase();
                        $(row).toggle(rowText.indexOf(searchTerm) !== -1);
                    });
                });
            }')
            ->orderBy(0)
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
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('action')
                ->title('Aksi')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
            Column::make('id')->title('Kode Transaksi'),
            Column::make('user_name')->title('Kasir'),
            Column::make('total_transaction')->title('Total Transaksi'),
            Column::make('created_at')->title('Dibuat Pada'),
            Column::make('updated_at')->title('Diedit Pada'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Transaction_' . date('YmdHis');
    }
}
