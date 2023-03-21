<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Traits\ImageUpload;
use DataTables;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Validator;

class BlogController extends Controller
{
    use ImageUpload;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Blog::query();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('cover', 'backend.page.blog.include.__cover')
                ->editColumn('title', 'backend.page.blog.include.__title')
                ->addColumn('action', 'backend.page.blog.include.__action')
                ->rawColumns(['cover', 'title', 'details', 'action'])
                ->make(true);
        }
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
            'cover' => 'required|image|mimes:jpg,png,svg',
            'title' => 'required',
            'details' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $input = $request->all();

        $data = [
            'cover' => self::imageUploadTrait($input['cover']),
            'title' => $input['title'],
            'details' => $input['details'],
        ];

        Blog::create($data);

        notify()->success(__(' Blog Create Successfully'));
        return redirect()->route('admin.page.edit', 'blog');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('backend.page.blog.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Blog $blog
     * @return Application|Factory|View
     */
    public function edit(Blog $blog)
    {
        return view('backend.page.blog.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Blog $blog
     * @return RedirectResponse
     */
    public function update(Request $request, Blog $blog)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'details' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $input = $request->all();

        $data = [
            'title' => $input['title'],
            'details' => $input['details'],
        ];

        if (isset($input['cover']) && is_file($input['cover'])) {
            $data['cover'] = self::imageUploadTrait($input['cover'], $blog->cover);
        }

        $blog->update($data);

        notify()->success(__('Blog Updated Successfully'));
        return redirect()->route('admin.page.edit', 'blog');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Blog $blog
     * @return RedirectResponse
     */
    public function destroy(Blog $blog)
    {
        if (file_exists('assets/'.$blog->cover)) {
            @unlink('assets/'.$blog->cover);
        }
        $blog->delete();

        notify()->success(__(' Blog Delete Successfully'));
        return redirect()->route('admin.page.edit', 'blog');
    }
}
