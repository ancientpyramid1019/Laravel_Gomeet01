<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Schema;
use App\Traits\ImageUpload;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class SchemaController extends Controller
{
    use ImageUpload;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    function __construct()
    {
        $this->middleware('permission:schema-list|schema-create|schema-edit', ['only' => ['index', 'store']]);
        $this->middleware('permission:schema-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:schema-edit', ['only' => ['edit', 'update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $schemas = Schema::all();
        return view('backend.schema.index', compact('schemas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'icon' => 'required',
            'name' => 'required',
            'type' => 'required',
            'min_amount' => 'required_if:type,==,range',
            'max_amount' => 'required_if:type,==,range',
            'fixed_amount' => 'required_if:type,==,fixed',
            'capital_back' => 'required',
            'featured' => 'required',
            'badge' => 'required_if:featured,==,1',
            'status' => 'required',
            'return_interest' => 'required',
            'interest_type' => 'required',
            'return_period' => 'required',
            'return_type' => 'required',
            'number_of_period' => 'required_if:return_type,==,period',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }


        $input = $request->all();

        $finalData = [
            "name" => $input['name'],

            "type" => $input['type'],
            "min_amount" => $input['type'] == 'fixed' ? 0 : $input['min_amount'],
            "max_amount" => $input['type'] == 'fixed' ? 0 : $input['max_amount'],
            "fixed_amount" => $input['type'] == 'range' ? 0 : $input['fixed_amount'],

            "capital_back" => $input['capital_back'],

            "featured" => $input['featured'],
            "badge" => $input['featured'] == 1 ? $input['badge'] : null,

            "status" => $input['status'],
            "return_interest" => $input['return_interest'],
            "interest_type" => $input['interest_type'],
            "return_period" => $input['return_period'],
            "return_type" => $input['return_type'],
            "number_of_period" => $input['return_type'] == 'period' ? $input['number_of_period'] : 0,
            "icon" => self::imageUploadTrait($input['icon']),
        ];

        Schema::create($finalData);

        notify()->success('schema created successfully');
        return redirect()->route('admin.schema.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $schedules = Schedule::all();

        return view('backend.schema.create', compact('schedules'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {

        $schedules = Schedule::all();
        $schema = Schema::find($id);

        return view('backend.schema.edit', compact('schema', 'schedules'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'min_amount' => 'required_if:type,==,range',
            'max_amount' => 'required_if:type,==,range',
            'fixed_amount' => 'required_if:type,==,fixed',
            'capital_back' => 'required',
            'featured' => 'required',
            'badge' => 'required_if:featured,==,1',
            'status' => 'required',
            'return_interest' => 'required',
            'interest_type' => 'required',
            'return_period' => 'required',
            'return_type' => 'required',
            'number_of_period' => 'required_if:return_type,==,period',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $schema = Schema::find($id);
        $input = $request->all();

        $finalData = [
            "name" => $input['name'],

            "type" => $input['type'],
            "min_amount" => $input['type'] == 'fixed' ? 0 : $input['min_amount'],
            "max_amount" => $input['type'] == 'fixed' ? 0 : $input['max_amount'],
            "fixed_amount" => $input['type'] == 'range' ? 0 : $input['fixed_amount'],

            "capital_back" => $input['capital_back'],

            "featured" => $input['featured'],
            "badge" => $input['featured'] == 1 ? $input['badge'] : null,

            "status" => $input['status'],
            "return_interest" => $input['return_interest'],
            "interest_type" => $input['interest_type'],
            "return_period" => $input['return_period'],
            "return_type" => $input['return_type'],
            "number_of_period" => $input['return_type'] == 'period' ? $input['number_of_period'] : 0,
            "icon" => $request->hasFile('icon') ? self::imageUploadTrait($input['icon']) : $schema->icon,
        ];

        $schema->update($finalData);

        notify()->success('schema Update successfully');
        return redirect()->route('admin.schema.index');
    }
}
