<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Schema;


class SchemaController extends Controller
{
    public function index()
    {
<<<<<<< HEAD
=======

>>>>>>> a8543ca (change style sun to the moon)
        $schemas = Schema::where('status', true)->with('schedule')->get();
        return view('frontend.schema.index', compact('schemas'));
    }

    public function schemaPreview($id)
    {

        $schemas = Schema::where('status',true)->with('schedule')->get();
        $schema = Schema::with('schedule')->find($id);
        return view('frontend.schema.preview', compact('schema', 'schemas'));
    }

    public function schemaSelect($id)
    {
        $schema = Schema::with('schedule')->find($id);
        $currency = setting('site_currency', 'global');

        return [
            'amount_range' => $schema->type == 'range' ? 'Minimum ' . $schema->min_amount . ' ' . $currency . ' - ' . 'Maximum ' . $schema->max_amount . ' ' . $currency : $schema->fixed_amount . ' ' . $currency,
            'return_interest' => ($schema->interest_type == 'percentage' ? $schema->return_interest . '%' : $schema->return_interest . ' ' . $currency) . ' (' . $schema->schedule->name . ')',
            'number_period' => ($schema->return_type == 'period' ? $schema->number_of_period : 'Unlimited') . ($schema->number_of_period == 1 ? ' Time' : ' Times'),
            'capital_back' => $schema->capital_back ? 'Yes' : 'No',
            'invest_amount' => $schema->type == 'fixed' ? $schema->fixed_amount : 0,
            'interest' => $schema->return_interest,
            'period' => $schema->number_of_period,
            'interest_type' => $schema->interest_type,
        ];

    }
}
