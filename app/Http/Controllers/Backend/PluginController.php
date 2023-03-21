<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Plugin;
use Illuminate\Http\Request;

class PluginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:plugin-setting');
    }

    public function plugin()
    {
        $plugins = Plugin::all();
        return view('backend.setting.plugin.index', compact('plugins'));
    }

    public function pluginData($id)
    {
        $plugin = Plugin::find($id);

        return view('backend.setting.plugin.include.__plugin_data', compact('plugin'))->render();
    }

    public function update(Request $request, $id)
    {
        $plugin = Plugin::find($id);

        $plugin->update([
            'data' => json_encode($request->data),
            'status' => $request->status
        ]);
        notify()->success(__('Settings has been saved'));
        return back();
    }
}
