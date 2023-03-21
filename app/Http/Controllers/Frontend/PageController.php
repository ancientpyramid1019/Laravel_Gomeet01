<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Page;
use App\Traits\MailSendTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Fluent;
use Mail;

class PageController extends Controller
{
    use  MailSendTrait;

    public function __invoke()
    {
        $url = request()->segment(1);
        $page = Page::where('url', $url)->first();

        if (!$page->status) {
            abort(404);
        }

        $data = new Fluent(json_decode($page->data, true));
        return view('frontend.pages.' . $url, compact('data'));
    }

    public function getPage($section)
    {
        $page = Page::where('code', $section)->where('type', 'dynamic')->where('status', true)->first();

        if (!$page) {
            abort(404);
        }

        $title = $page->title;
        $data = new Fluent(json_decode($page->data, true));

        return view('frontend.pages.dynamic_page', compact('data', 'title'));
    }

    public function blogDetails($id)
    {

        $blogInstance = new Blog();

        $blog = $blogInstance->find($id);

        $blogs = $blogInstance->where('id','!=',$id)->pluck('title', 'id');


        $page = Page::where('code', 'blog')->first();


        $data = new Fluent(json_decode($page->data, true));

        return view('frontend.pages.blog_details', compact('blog', 'blogs', 'data'));
    }

    //mail send function
    public function mailSend(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'msg' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }



        try {


            $input = $request->all();

            $shortcodes = [
                '[[full_name]]' => $input['name'],
                '[[email]]' => $input['email'],
                '[[subject]]' => $input['subject'],
                '[[message]]' => $input['msg'],
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
            ];


            $this->mailSendWithTemplate(setting('support_email', 'global'), 'contact_mail', $shortcodes);
            $status = 'success';
            $message = __('Successfully Send Message');

        } catch (Exception $e) {

            $status = 'warning';
            $message = __('something is wrong');
        }

        notify()->$status($message, $status);
        return redirect()->back();

    }
}
