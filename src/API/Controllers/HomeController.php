<?php

declare(strict_types=1);

namespace Mai1015\PlusIllusts\API\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Mai1015\PlusIllusts\Models\Illust;

class HomeController
{
    /**
     * testing api
     *
     * @return \Illuminate\Http\JsonResponse
     * @author mai1015 <i@mai1015.com>
     */
    public function index(Request $request)
    {
        $path = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('public/data');
        }
        if ($request->exists('read')) {
            $path = Storage::url($request->input('read'));
        }
        if ($request->exists('illust')) {
            return response()->json([
               'tags' => Illust::find($request->input('illust'))->tags()->get(),
            ]);
        }
        return response()->json([
            'code' => 0,
            'message' => trans('plus-illusts::messages.success'),
            'datetime' => Carbon::now(),
            'path' => $path
        ]);
    }
}
