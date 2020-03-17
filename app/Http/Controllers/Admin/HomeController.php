<?php

namespace App\Http\Controllers\Admin;

use App\Payment;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class HomeController
{
    public function index()
    {
        $revenueChart = null;

        if (!auth()->user()->can('dashboard')) {
            return redirect()->route('admin.tasks.index');
        }

        if (Payment::count() === 0) {
            return view('home', compact('revenueChart'));
        }

        $chart_options = [
            'chart_title'         => 'Revenue by day',
            'report_type'         => 'group_by_date',
            'model'               => 'App\Payment',
            'group_by_field'      => 'created_at',
            'group_by_period'     => 'day',
            'aggregate_function'  => 'sum',
            'aggregate_field'     => 'paid_amount',
            'aggregate_transform' => function ($value) {
                return round($value / 100, 2);
            },
            'chart_type'          => 'bar',
            'filter_field'        => 'created_at',
            'filter_days'         => 30,
            'continuous_time'     => true,
        ];

        $revenueChart = new LaravelChart($chart_options);

        return view('home', compact('revenueChart'));
    }
}
