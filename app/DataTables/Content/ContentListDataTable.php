<?php

namespace App\DataTables\Content;

use App\Models\Content;
use Carbon\Carbon;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\JsonResponse;

class ContentListDataTable extends DataTable
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
            ->editColumn('title', function($data){
                return ucfirst($data->title) ?? '-';
            })
            ->editColumn('status', function ($data) {
                if ($data->status === 'pending'){
                    return "<label class='badge bg-warning'> Pending </label>";
                }else if($data->status === 'approved'){
                    return "<label class='badge bg-success'> Approved </label>";
                }
                return "<label class='badge bg-danger'> Rejected </label>";
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at ? Carbon::parse($data->created_at)->format('M d, Y h:i A') : '-';
            })
            ->editColumn('published_at', function ($data) {
                return $data->published_at ? Carbon::parse($data->published_at)->format('M d, Y h:i A') : '-';
            })
            ->editColumn('rejected_at', function ($data) {
                return $data->rejected_at ? Carbon::parse($data->rejected_at)->format('M d, Y h:i A') : '-';
            })
            ->addColumn('action', function ($data) {
                if(auth()->user()->is_admin && $data->status === 'pending'){

                    $actionBtn = '<a href="' . route('contents.approve', $data->id) . '" class="btn btn-xs btn-primary btn-sm" title="Approve"> <i class="bi bi-check-circle"></i> Approve</a> ';
                    $actionBtn .= '<a href="' . route('contents.reject', $data->id) . '" class="btn btn-xs btn-danger btn-sm" title="Reject" onclick="return confirm(\'Are you sure you want to reject this item?\')"> <i class="bi bi-x-circle"></i> Reject</a>';
    
                    return $actionBtn;
                }
                
               return '-';

            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function getContentList()
    {
        $contentQuery = Content::leftJoin('users', 'users.id', '=', 'contents.user_id');
        if(!auth()->user()->is_admin){
            $contentQuery->where('contents.user_id', auth()->id());
        }
        
        return $contentQuery->select(['contents.*', 'users.name'])->latest();
    }

    /**s
     * Get query source of dataTable.
     * @return \Illuminate\Database\Eloquent\Builder
     * @internal param \App\Models\AgentBalanceTransactionHistory $model
     */
    public function query()
    {
        return $this->applyScopes($this->getContentList());
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
            'created_at'       => ['data' => 'created_at', 'name' => 'contents.created_at', 'orderable' => true, 'searchable' => false],
            'title'            => ['data' => 'title', 'name' => 'contents.title', 'orderable' => true, 'searchable' => true],
            'user'             => ['data' => 'name', 'name' => 'users.name', 'orderable' => true, 'searchable' => true, 'title' => 'Created By'],
            'status'           => ['data' => 'status', 'name' => 'contents.status', 'orderable' => true, 'searchable' => false],
            'published_at'     => ['data' => 'published_at', 'name' => 'contents.published_at', 'orderable' => true, 'searchable' => false],
            'rejected_at'      => ['data' => 'rejected_at', 'name' => 'contents.rejected_at', 'orderable' => true, 'searchable' => false],
            'action'           => ['searchable' => false, 'orderable' => false]
        ];
    }
    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Content_List_' . date('Y_m_d_H_i_s') . '.json';
    }
}