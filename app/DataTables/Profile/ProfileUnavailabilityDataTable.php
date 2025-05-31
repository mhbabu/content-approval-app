<?php

namespace App\DataTables\Profile;

use App\Models\DateOverride;
use Carbon\Carbon;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\JsonResponse;

class ProfileUnavailabilityDataTable extends DataTable
{
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function ajax(): JsonResponse
    {
        return datatables()
            ->eloquent($this->query())
            ->addColumn('day_of_week', function($data){
                return $data->date ? Carbon::parse($data->date)->format('l') : '-';
            })
            ->make(true);
    }

    public function weeklyUnavailabilityList()
    {
        return DateOverride::where('profile_id', $this->profile_id)->oldest('date');  
            
    }

    /**s
     * Get query source of dataTable.
     * @return \Illuminate\Database\Eloquent\Builder
     * @internal param \App\Models\AgentBalanceTransactionHistory $model
     */
    public function query()
    {
        return $this->applyScopes($this->weeklyUnavailabilityList());
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->parameters([
                'dom' => 'Blfrtip',
                'responsive' => true,
                'autoWidth' => false,
                'paging' => true,
                "pagingType" => "full_numbers",
                'searching' => true,
                'info' => true,
                'searchDelay' => 350,
                "serverSide" => true,
                'order' => [[1, 'asc']],
                'buttons' => [],
                'pageLength' => 10,
                'lengthMenu' => [[10, 20, 25, 50, 100, 500, -1], [10, 20, 25, 50, 100, 500, 'All']],
                'language' => [
                    'lengthMenu' => '<span class="length-menu-text">Show</span> _MENU_ <span class="length-menu-text">entries</span>',
                    'paginate' => [
                        'first'    => '&laquo;',
                        'previous' => '&lsaquo;',
                        'next'     => '&rsaquo;',
                        'last'     => '&raquo;',
                    ],
                ]
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'date'           => ['data' => 'date', 'name' => 'date', 'orderable' => true, 'searchable' => true],
            'day_of_week'    => ['data' => 'day_of_week', 'day_of_week' => 'date', 'orderable' => true, 'searchable' => false],
        ];
    }
    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Weekly_Unavailability_List' . date('Y_m_d_H_i_s') . '.json';
    }
}
