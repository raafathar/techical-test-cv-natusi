<?php

namespace App\DataTables;

use App\Models\Distributor;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class DistributorDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Distributor $distributor) {
                return view('dashboard.distributor.distributors.action', compact('distributor'));
            })
            ->editColumn('created_at', function (Distributor $distributor) {
                return $distributor->created_at
                    ->timezone('Asia/Jakarta')
                    ->translatedFormat('d F Y, H:i');
            })
            ->editColumn('updated_at', function (Distributor $distributor) {
                return $distributor->updated_at
                    ->timezone('Asia/Jakarta')
                    ->translatedFormat('d F Y, H:i');
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Distributor $model): QueryBuilder
    {
        return $model->newQuery()
            ->select(['id', 'nama', 'alamat', 'telepon', 'latitude', 'longitude', 'created_at', 'updated_at']);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('distributor-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax(route('dashboard.distributor.distributors.index'))
                    //->dom('Bfrtip')
                    ->parameters([
                        'searching' => false,
                    ])
                    ->initComplete('function(settings, json) {
                        var table = window.LaravelDataTables[\'distributor-table\'];
                
                        $(\'#input-search\').on(\'keyup\', function() {
                            var searchTerm = $(this).val().toLowerCase();
                
                            table.rows().every(function() {
                                var row = this.node();
                                var rowText = row.textContent.toLowerCase();
                
                                if (rowText.indexOf(searchTerm) === -1) {
                                    $(row).hide();
                                } else {
                                    $(row).show();
                                }
                            });
                        });
                    }')
                    ->orderBy(5)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center')
                ->title('Action'),
            Column::make('nama')->title('Nama'),
            Column::make('alamat')->title('Alamat'),
            Column::make('telepon')->title('Telepon'),
            Column::make('created_at')->title('Dibuat Pada'),
            Column::make('updated_at')->title('Diedit Pada'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'distributor_' . date('YmdHis');
    }
}
