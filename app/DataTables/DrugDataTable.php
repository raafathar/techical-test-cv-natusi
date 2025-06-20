<?php

namespace App\DataTables;

use App\Models\Drug;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DrugDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Drug $drug) {
                return view('dashboard.drug.drungs.action', compact('drug'));
            })
            ->editColumn('created_at', function (Drug $drug) {
                return $drug->created_at
                    ->timezone('Asia/Jakarta')
                    ->translatedFormat('d F Y, H:i');
            })
            ->editColumn('updated_at', function (Drug $drug) {
                return $drug->updated_at
                    ->timezone('Asia/Jakarta')
                    ->translatedFormat('d F Y, H:i');
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Drug $model): QueryBuilder
    {
        $query = $model->newQuery();

        $searchTerm = request()->input('search')['value'] ?? null;

        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%')
                    ->orWhere('address', 'like', '%' . $searchTerm . '%')
                    ->orWhere('created_at', 'like', '%' . $searchTerm . '%')
                    ->orWhere('updated_at', 'like', '%' . $searchTerm . '%');
            });
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('drug-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax(route('dashboard.drug.drugs.index'))
                    //->dom('Bfrtip')
                    ->parameters([
                        'searching' => false,
                    ])
                    ->initComplete('function(settings, json) {
                        var table = window.LaravelDataTables[\'drug-table\'];
                
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
            Column::make('kode_obat')->title('Kode'),
            Column::make('nama_obat')->title('Nama'),
            Column::make('satuan_obat')->title('Satuan'),
            Column::make('harga_obat')->title('Harga'),
            Column::make('stock_obat')->title('Stok'),
        Column::make('created_at')->title('Dibuat Pada'),
        Column::make('updated_at')->title('Diedit Pada'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Drug_' . date('YmdHis');
    }
}
