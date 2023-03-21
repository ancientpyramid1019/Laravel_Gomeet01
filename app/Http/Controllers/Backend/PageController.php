<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LandingContent;
use App\Models\LandingPage;
use App\Models\Page;
use App\Models\PageSetting;
use App\Models\Social;
use App\Traits\ImageUpload;
use Arr;
use Cache;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Fluent;
use Mews\Purifier\Facades\Purifier;
use Str;

class PageController extends Controller
{
    use ImageUpload;

    /**
     *
     */
    function __construct()
    {
        $this->middleware('permission:footer-manage|landing-page-manage', ['only' => ['landingSectionUpdate']]);
        $this->middleware('permission:landing-page-manage', ['only' => ['landingSection', 'contentStore', 'contentUpdate', 'contentDelete']]);
        $this->middleware('permission:page-manage', ['only' => ['create', 'store', 'edit', 'update', 'deleteNow']]);
        $this->middleware('permission:footer-manage', ['only' => ['footerContent']]);

        $this->middleware('permission:page-setting', ['only' => ['pageSetting', 'pageSettingUpdate', 'settingUpdate']]);
    }

    //================================== page section ===============================================

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $input = $request->all();
        $slug = str::slug($input['title'], '-');

        $page = new Page();

        if ($page->where('code', $slug)->exists()) {
            notify()->error('Same Name Already Exists', 'Error');
            return redirect()->back();
        }


        $content = [
            'meta_keywords' => $input['meta_keywords'],
            'meta_description' => $input['meta_description'],
            'section_id' => $input['section_id'] ?? null,
            'content' => Purifier::clean(htmlspecialchars_decode($input['content'])),
        ];


        $page->create([
            'title' => $input['title'],
            'url' => '/page/' . $slug,
            'code' => $slug,
            'data' => json_encode($content),
            'status' => $input['status'],
        ]);

        Cache::pull('pages');

        notify()->success(__('New Page Created Successfully'));
        return redirect()->back();
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        $landingSections = Cache::get('landingSections');

        return view('backend.page.create', compact('landingSections'));
    }

    /**
     * @param $name
     * @return Application|Factory|View
     */
    public function edit($name)
    {
        $page = Page::where('code', $name)->first();

        $status = $page->status;
        $data = new Fluent(json_decode($page->data, true));

        if ($page->type == 'dynamic') {
            $landingSections = Cache::get('landingSections');
            $title = $page->title;
            $code = $page->code;
            return view('backend.page.edit', compact('landingSections', 'title', 'data', 'status', 'code'));
        }

        return view('backend.page.' . $name, compact('status', 'data'));

    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteNow(Request $request)
    {
        $pageCode = $request['page_code'];
        $page = Page::where('code', $pageCode)->first();
        $page->delete();
        Cache::pull('pages');
        notify()->success(__('Deleted Successfully'));
        return redirect()->route('admin.page.create');
    }

    /**
     * @param $section
     * @return Application|Factory|View
     */
    public function landingSection($section)
    {

        $landingPage = LandingPage::where('code', $section)->first();
        $landingContent = LandingContent::where('type', $section)->get();

        $status = $landingPage->status;
        $data = new Fluent(json_decode($landingPage->data, true));
        return view('backend.page.section.' . $section, compact('status', 'data', 'landingContent'));
    }




    //================================== Landing Section ===============================================

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function landingSectionUpdate(Request $request)
    {

        $input = $request->all();
        $data = $request->except(['section_code', 'status', '_token']);

        $sectionCode = $input['section_code'];
        $landingPage = LandingPage::where('code', $sectionCode)->first();


        $oldData = json_decode($landingPage->data, true);

        foreach ($data as $key => $value) {

            if (is_file($value)) {
                $oldValue = Arr::get($oldData, $key);
                $data[$key] = self::imageUploadTrait($value, $oldValue);
            }
        }

        $data = array_merge($oldData, $data);

        $landingPage->update([
            'status' => $input['status'] ?? 1,
            'data' => json_encode($data)
        ]);

        notify()->success($landingPage->name . ' ' . __(' Updated Successfully'));
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request)
    {

        $input = $request->all();

        $content = $request->except(['page_code', 'status', '_token']);

        $pageCode = $input['page_code'];
        $page = Page::where('code', $pageCode)->first();

        if ($page->type == 'dynamic') {
            $content = [
                'section_id' => $input['section_id'] ?? null,
                'meta_keywords' => $input['meta_keywords'],
                'meta_description' => $input['meta_description'],
                'content' => Purifier::clean(htmlspecialchars_decode($input['content'])),
            ];

            $data = [
                'title' => $input['title'],
                'data' => json_encode($content),
                'status' => $input['status'],
            ];

        } else {

            $oldData = json_decode($page->data, true);
            foreach ($content as $key => $value) {

                if (is_file($value)) {
                    $oldValue = Arr::get($oldData, $key);
                    $content[$key] = self::imageUploadTrait($value, $oldValue);

                } elseif ($key == 'content') {
                    $content[$key] = Purifier::clean(htmlspecialchars_decode($value));
                }
            }

            $content = array_merge($oldData, $content);

            $data = [
                'status' => $input['status'] ?? true,
                'data' => json_encode($content)
            ];

        }

        $page->update($data);

        if ($page->type == 'dynamic') Cache::pull('pages');

        notify()->success($page->title . ' ' . __(' Updated Successfully'));
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function contentStore(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $input = $request->all();

        $data = [
            'icon' => $input['icon'] ?? null,
            'title' => $input['title'],
            'description' => $input['description'],
            'type' => $input['type'],
        ];

        if ($request->hasFile('icon')) {
            $data = array_merge($data, ['icon' => self::imageUploadTrait($input['icon'])]);
        }

        LandingContent::create($data);
        notify()->success(__(' Content Create Successfully'));
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function contentUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $input = $request->all();

        $landingContent = LandingContent::find($input['id']);

        $data = [
            'icon' => $input['icon'] ?? $landingContent->icon,
            'title' => $input['title'],
            'description' => $input['description'],
        ];


        if ($request->hasFile('icon')) {
            $data = array_merge($data, ['icon' => self::imageUploadTrait($input['icon'], $landingContent->icon)]);
        }


        $landingContent->update($data);
        notify()->success(__(' Content Update Successfully'));
        return redirect()->back();

    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function contentDelete(Request $request)
    {
        $id = $request->id;
        LandingContent::find($id)->delete();
        notify()->success(__('Content Delete Successfully'));
        return redirect()->back();
    }

    //================================== End Landing Section ===============================================


    /**
     * @return Application|Factory|View
     */
    public function pageSetting()
    {
        return view('backend.page.setting');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function pageSettingUpdate(Request $request)
    {

        $input = $request->all();
        if (isset($input['breadcrumb']) && is_file($input['breadcrumb'])) {

            $breadcrumb = self::imageUploadTrait($input['breadcrumb'], getPageSetting('breadcrumb'));
            $this->settingUpdate('breadcrumb', $breadcrumb);
        }

        notify()->success(__('Page Setting Update Successfully'));
        return redirect()->back();
    }

    /**
     * @param $key
     * @param $value
     * @return void
     */
    private function settingUpdate($key, $value)
    {
        PageSetting::where('key', $key)->update(['value' => $value]);
    }


    /**
     * @return Application|Factory|View
     */
    public function footerContent()
    {
        $socials = Social::orderBy('position')->get();
        $landingPage = LandingPage::where('code', 'footer')->first();
        $data = new Fluent(json_decode($landingPage->data, true));
        return view('backend.page.section.footer', compact('data', 'socials'));
    }
}
