<?php

namespace App\DataTables\Profile;

use App\Models\WeeklyAvailability;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\JsonResponse;

class ProfileAvailabilityDataTable extends DataTable
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
            ->editColumn('start_time', function($data){
               return $data->start_time ? date('h:i A', strtotime($data->start_time)) : '-';
            })
            ->editColumn('end_time', function($data){
                return $data->end_time ? date('h:i A', strtotime($data->end_time)) : '-';
             })
            ->editColumn('status', function ($data) {
                if ($data->status === 1) return "<label class='badge bg-success'> Active </label>";
                else if ($data->status === 2) return "<label class='badge bg-warning'> Pending</label>";
                return "<label class='badge bg-danger'> Rejected/Banned </label>";
            })
            ->editColumn('category_name', function($data){
                return ucfirst($data->category_name) ?? '-';
            })
            ->rawColumns(['status'])
            ->make(true);
    }

    public function weeklyAvailabilityList()
    {
        return WeeklyAvailability::where('profile_id', $this->profile_id)->latest();  
            
    }

    /**s
     * Get query source of dataTable.
     * @return \Illuminate\Database\Eloquent\Builder
     * @internal param \App\Models\AgentBalanceTransactionHistory $model
     */
    public function query()
    {
        return $this->applyScopes($this->weeklyAvailabilityList());
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
            'day_of_week'   => ['data' => 'day_of_week', 'name' => 'day_of_week', 'orderable' => true, 'searchable' => true],
            'start_time'    => ['data' => 'start_time', 'name' => 'start_time', 'orderable' => true, 'searchable' => true],
            'end_time'        => ['end_time' => 'status', 'name' => 'end_time', 'orderable' => true, 'searchable' => true]
        ];
    }
    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Weekly_Availability_List' . date('Y_m_d_H_i_s') . '.json';
    }
}
