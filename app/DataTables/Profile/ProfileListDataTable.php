<?php

namespace App\DataTables\Profile;

use App\Models\Profile;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\JsonResponse;

class ProfileListDataTable extends DataTable
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
            ->editColumn('status', function ($data) {
                if ($data->status === 1) return "<label class='badge bg-success'> Active </label>";
                else if ($data->status === 2) return "<label class='badge bg-warning'> Pending</label>";
                return "<label class='badge bg-danger'> Rejected/Banned </label>";
            })
            ->editColumn('category_name', function($data){
                return ucfirst($data->category_name) ?? '-';
            })
            ->addColumn('action', function ($data) {
                $actionBtn = '';
                $actionBtn = '<a href="' . route('override-availabilites.show', $data->id) . '" class="btn btn-xs btn-warning btn-sm" title="Details"> <i class="bi bi-list"></i> Unavailability</a> ';
                $actionBtn .= '<a href="' . route('service-profiles.show', $data->id) . '" class="btn btn-xs btn-info btn-sm" title="Details"> <i class="bi bi-list"></i> Availability</a> ';
                $actionBtn .= '<a href="' . route('service-profiles.edit', $data->id) . '" class="btn btn-xs btn-primary btn-sm" title="Edit"> <i class="bi bi-pencil"></i> Edit</a> ';
                $actionBtn .= '<a href="' . route('service-profiles.delete', $data->id) . '" class="btn btn-xs btn-danger btn-sm" title="Delete" onclick="return confirm(\'Are you sure you want to delete this item?\')"> <i class="bi bi-trash"></i> Delete</a>';

                return $actionBtn;
               
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function getProfileList()
    {
        $query =  Profile::leftJoin('users', 'users.id', '=', 'profiles.user_id')
            ->leftJoin('categories', 'categories.id', '=', 'profiles.category_id');
            
        if(!auth()->user()->is_admin)
            $query->where('profiles.user_id', auth()->id());
        
        return $query->select(['profiles.*', 'users.name as user_name', 'categories.name as category_name'])
        ->orderBy('profiles.id', 'desc');    
            
    }

    /**s
     * Get query source of dataTable.
     * @return \Illuminate\Database\Eloquent\Builder
     * @internal param \App\Models\AgentBalanceTransactionHistory $model
     */
    public function query()
    {
        return $this->applyScopes($this->getProfileList());
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
            'user_name'     => ['data' => 'user_name', 'name' => 'users.name', 'orderable' => true, 'searchable' => true],
            'tile'          => ['data' => 'title', 'name' => 'profiles.title', 'orderable' => true, 'searchable' => true, 'title' => 'Profile Title'],
            'category_name' => ['data' => 'category_name', 'name' => 'categories.name', 'orderable' => true, 'searchable' => false],
            'timezone'      => ['data' => 'timezone', 'name' => 'users.timezone', 'orderable' => true, 'searchable' => false],
            'status'        => ['data' => 'status', 'name' => 'profiles.status', 'orderable' => true, 'searchable' => false],
            'action'        => ['searchable' => false, 'orderable' => false]
        ];
    }
    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Profile_List' . date('Y_m_d_H_i_s') . '.json';
    }
}
