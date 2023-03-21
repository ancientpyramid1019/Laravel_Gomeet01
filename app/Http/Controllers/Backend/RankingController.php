<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Ranking;
use App\Traits\ImageUpload;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Validator;

class RankingController extends Controller
{
    use ImageUpload;

    function __construct()
    {
        $this->middleware('permission:ranking-list|ranking-create|ranking-edit', ['only' => ['index', 'store']]);
        $this->middleware('permission:ranking-create', ['only' => ['store']]);
        $this->middleware('permission:ranking-edit', ['only' => ['update']]);
        $this->middleware('permission:ranking-delete', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $rankings = Ranking::all();
        return view('backend.ranking.index', compact('rankings'));
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
            'icon' => 'required|image|mimes:jpg,png,svg',
            'ranking' => 'required|unique:rankings,ranking',
            'ranking_name' => 'required|unique:rankings,ranking_name',
            'minimum_earnings' => 'required|regex:/^\d+(\.\d{1,2})?$/|unique:rankings,minimum_earnings',
            'bonus' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'description' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $input = $request->all();

        Ranking::create([
            'icon' => self::imageUploadTrait($input['icon']),
            'ranking' => $input['ranking'],
            'ranking_name' => $input['ranking_name'],
            'minimum_earnings' => $input['minimum_earnings'],
            'bonus' => $input['bonus'],
            'description' => $input['description'],
            'status' => $input['status'],
        ]);

        notify()->success('Ranking created successfully');
        return redirect()->route('admin.ranking.index');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param \App\Models\Ranking $ranking
     * @return RedirectResponse
     */
    public function update(Request $request, Ranking $ranking)
    {

        $validator = Validator::make($request->all(), [
            'ranking' => 'required|unique:rankings,ranking,' . $ranking->id,
            'ranking_name' => 'required|unique:rankings,ranking_name,' . $ranking->id,
            'minimum_earnings' => 'required|regex:/^\d+(\.\d{1,2})?$/|unique:rankings,minimum_earnings,' . $ranking->id,
            'bonus' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'description' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $input = $request->all();


        if ($ranking->id == 1 && $input['status'] == 0){
            notify()->error('Default Rank Status Not Updated', 'Error');
            return redirect()->back();
        }

        $data = [
            'ranking' => $input['ranking'],
            'ranking_name' => $input['ranking_name'],
            'minimum_earnings' => $input['minimum_earnings'],
            'bonus' => $input['bonus'],
            'description' => $input['description'],
            'status' => $input['status'],
        ];

        if ($request->hasFile('icon')) {
            $icon = self::imageUploadTrait($input['icon'], $ranking->icon);
            $data = array_merge($data, ['icon' => $icon]);
        }


        $ranking->update($data);

        notify()->success('Ranking Updated successfully');
        return redirect()->route('admin.ranking.index');
    }

}
