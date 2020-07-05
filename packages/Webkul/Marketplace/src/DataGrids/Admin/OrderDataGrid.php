<?php

namespace Webkul\Marketplace\DataGrids\Admin;

use Webkul\Ui\DataGrid\DataGrid;
use Illuminate\Support\Facades\DB;

class OrderDataGrid extends DataGrid
{
    protected $sortOrder = 'desc';

    protected $index = 'order_id';

    protected $itemsPerPage = 10;
    
    public function prepareQueryBuilder()
    {
    
        $queryBuilder = 
                
            DB::table('seller_orders as order_seller')
            ->join('orders as order', 'order.id', '=', 'order_seller.order_id')
            ->join('sellers as seller', 'order_seller.seller_id', '=', 'seller.id')
            ->join('customers as customer', 'customer.id', '=', 'seller.customer_id')
                
                
                
                // be carefull with one to many join
      
            
           
//            ->leftJoin('seller_invoices as seller_invoice','seller_invoice.order_id', '=', 'order_seller.id')    
                
           
                
                
            ->addSelect('order.id as order_id','order_seller.grand_total','order.customer_first_name','order.status','order.created_at','customer.first_name as seller_name','order_seller.commission as commission','order_seller.seller_total as seller_total','order_seller.discount_amount as discount','order_seller.seller_id','order_seller.id as seller_order_id','order_seller.total_paid as paid',DB::raw('order_seller.seller_total_invoiced-order_seller.total_paid as remaining'),'order_seller.seller_total_invoiced as invoiced','order_seller.status as order_status')
                
           ;    
   

        $this->addFilter('order_id','order.id');
        $this->setQueryBuilder($queryBuilder);
      
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'order_id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        
        $this->addColumn([
            'index'      => 'grand_total',
            'label'      => 'Grand Total',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        
         $this->addColumn([
            'index'      => 'customer_first_name',
            'label'      => 'Billed To',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
         
         $this->addColumn([
            'index'      => 'status',
            'label'      => 'Status',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => true,
            'wrapper'    => function ($value) {
                if ($value->status == 'processing') {
                    return '<span class="badge badge-md badge-success">' . trans('shop::app.customer.account.order.index.processing') . '</span>';
                } elseif ($value->status == 'completed') {
                    return '<span class="badge badge-md badge-success">' . trans('shop::app.customer.account.order.index.completed') . '</span>';
                } elseif ($value->status == "canceled") {
                    return '<span class="badge badge-md badge-danger">' . trans('shop::app.customer.account.order.index.canceled') . '</span>';
                } elseif ($value->status == "closed") {
                    return '<span class="badge badge-md badge-info">' . trans('shop::app.customer.account.order.index.closed') . '</span>';
                } elseif ($value->status == "pending") {
                    return '<span class="badge badge-md badge-warning">' . trans('shop::app.customer.account.order.index.pending') . '</span>';
                } elseif ($value->status == "pending_payment") {
                    return '<span class="badge badge-md badge-warning">' . trans('shop::app.customer.account.order.index.pending-payment') . '</span>';
                } elseif ($value->status == "fraud") {
                    return '<span class="badge badge-md badge-danger">' . trans('shop::app.customer.account.order.index.fraud') . '</span>';
                }
            },
        ]);
        $this->addColumn([
            'index'      => 'created_at',
            'label'      => 'Order Date',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        
        $this->addColumn([
            'index'      => 'seller_name',
            'label'      => 'Seller name',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        
        $this->addColumn([
            'index'      => 'commission',
            'label'      => 'Commission',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        
        $this->addColumn([
            'index'      => 'discount',
            'label'      => 'Discount',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        
        $this->addColumn([
            'index'      => 'seller_total',
            'label'      => 'Seller Total',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        
        $this->addColumn([
            'index'      => 'invoiced',
            'label'      => 'Invoiced',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        
        $this->addColumn([
            'index'      => 'paid',
            'label'      => 'Paid',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        
        $this->addColumn([
            'index'      => 'remaining',
            'label'      => 'Remaining',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        
        $this->addColumn([
            'index'      => 'pay',
            'label'      => 'Pay',
            'closure'    => true,
            'wrapper'    => function ($value) {
            if($value->order_status == 'Pay')  {  
            
                  return '<button id="pay_button" data-id="'.$value->seller_order_id.'" seller-id="'.$value->seller_id.'" class="btn btn-sm btn-primary pay-btn" data-remaining="'.$value->remaining.'"  data-seller-total="'.$value->seller_total.'">Pay</button>';
            }else{
                return $value->order_status;
            }
            },
        ]);
        
        
       
    }
}