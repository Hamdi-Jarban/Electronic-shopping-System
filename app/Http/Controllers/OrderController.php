<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    //
    public function index(Request $request)
    {
        $status      = $request->get('status');
        $payment     = $request->get('payment_status');
        $search      = $request->get('search');
        $dateFrom    = $request->get('date_from');
        $dateTo      = $request->get('date_to');
        $sortBy      = $request->get('sort_by', 'newest');

        // بناء الاستعلام الأساسي
        $query = DB::table('order_header as o')
            ->leftJoin('user as u', 'o.user_id', '=', 'u.user_id')
            ->leftJoin('payment as pay', 'pay.order_id', '=', 'o.order_id')
            ->leftJoin('shipment as s', 's.order_id', '=', 'o.order_id')
            ->leftJoin('order_delivery as d', 'd.order_id', '=', 'o.order_id')
            ->leftJoin('delivery_driver as dr', 'd.driver_id', '=', 'dr.driver_id')
            ->select(
                'o.order_id',
                'o.order_date',
                'o.total_amount',
                'o.order_status',
                'u.full_name as customer_name',
                'u.email as customer_email',
                'u.phone as customer_phone',
                'pay.payment_status',
                'pay.amount as paid_amount',
                's.tracking_number',
                's.shipment_status',
                'd.delivery_status',
                'dr.driver_name'
            );
        if ($status) {
            $query->where('o.order_status', $status);
        }
        if ($payment) {
            $query->where('pay.payment_id', $payment);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('o.order_id', 'like', "%{$search}%")
                    ->orwhere('u.full_name', 'like', "%{$search}%")
                    ->orwhere('u.email', 'like', "%{$search}%");
            });
        }
        if ($dateFrom) {
            $query->whereDate('o.order_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('o.order_date', '<=', $dateTo);
        }
        switch ($sortBy) {
            case 'oldest':
                $query->orderBy('o.order_date', 'asc');
                break;
            case 'amount_asc':
                $query->orderBy('o.total_amount', 'asc');
                break;
            case 'amount_desc':
                $query->orderBy('o.total_amount', 'desc');
                break;
            default:
                $query->orderBy('o.order_date', 'desc');
        }
        $orders = $query->groupBy(
            'o.order_id',
            'o.order_date',
            'o.total_amount',
            'o.order_status',
            'u.full_name',
            'u.email',
            'u.phone',
            'pay.payment_status',
            'pay.amount',
            's.tracking_number',
            's.shipment_status',
            'd.delivery_status',
            'dr.driver_name'
        )->paginate(15)
            ->appends($request->query());

        $stats = [
            'total'      => DB::table('order_header')->count(),
            'pending'    => DB::table('order_header')->where('order_status', 'pending')->count(),
            'processing' => DB::table('order_header')->whereIn('order_status', ['confirmed', 'processing'])->count(),
            'shipped'    => DB::table('order_header')->where('order_status', 'shipped')->count(),
            'delivered'  => DB::table('order_header')->where('order_status', 'delivered')->count(),
            'cancelled'  => DB::table('order_header')->where('order_status', 'cancelled')->count(),
            'revenue'    => DB::table('order_header')->where('order_status', '!=', 'cancelled')->sum('total_amount'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }
}
