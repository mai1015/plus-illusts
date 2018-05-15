<?php
/**
 * Created by PhpStorm.
 * User: mai1015
 * Date: 2018-04-09
 * Time: 20:12
 */

namespace Mai1015\PlusIllusts\API\Controllers;

use Mai1015\PlusIllusts\Models\PixivUser;
use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
class CrawlerController
{
    /**
     * display one user to cralwer
     *
     * @return \Illuminate\Http\JsonResponse
     * @author mai1015 <i@mai1015.com>
     */
    public function show(Request $request, ResponseFactoryContract $response)
    {
        $user = PixivUser::where('updated_at', '<', date('Y-m-d H:i:s', strtotime('+1 days')))
            ->orderBy('updated_at','asc')->where('status', '=', 0)->first();
        if (!is_null($user)) {
            $user->touch();
        }
        return response()->json([
            'code' => 0,
            'message' => trans('plus-illusts::messages.success'),
            'user' => $user,
        ]);
    }

    public function store(Request $request, ResponseFactoryContract $response)
    {
        $pixivid = $request->input('pixivid');
        $name = $request->input('name');
        $avatar = $request->input('avatar');

        $ret = [
            'code' => 0,
            'message' => trans('plus-illusts::messages.dupulicated'),
            'pixivid' => $pixivid
        ];

        if (PixivUser::find($pixivid)) {
            return $response->json($ret);
        }

        $user = new PixivUser();
        $user->id = $pixivid;
        $user->name = $name;
        $user->status = 0;
        $user->avatar = $avatar;

        if (! $user->save()) {
            $ret['message'] = trans('plus-illusts::messages.success');
            return $response->json($ret)->setStatusCode(201);
        }

        $ret['code'] = 500;
        $ret['message'] = trans('plus-illusts::messages.fail');
        return $response->json($ret);
    }
}