<?php
/**
 * Created by PhpStorm.
 * User: mai1015
 * Date: 2018-04-10
 * Time: 13:10
 */

namespace Mai1015\PlusIllusts\API\Controllers;

use Illuminate\Support\Facades\Storage;
use Mai1015\PlusIllusts\Models\PixivUser;
use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class PixivUserController
{
    protected $path;

    /**
     * Create the Controller.
     *
     * @param \Illuminate\Contracts\Config\Repository $config
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(ConfigRepository $config)
    {
        //$this->config = $config;
        $this->path = $config->get('plus-illusts.avatar_path');
    }

    public function show(PixivUser $user)
    {
        return $user;
    }

    /**
     * Create new pixiv user
     * @param Request $request
     * @param ResponseFactoryContract $response
     * @return \Illuminate\Http\JsonResponse
     * @author mai1015 <i@mai1015.com>
     */
    public function store(Request $request, ResponseFactoryContract $response)
    {
        $pixivid = $request->input('pixivid');
        $name = $request->input('name');
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
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $ext = $file->getClientOriginalExtension();
            $hash  = $request->input('hash');
            $path = $file->storeAs($this->path, $hash.'.'.$ext);

            $user->avatar_url = $request->input('avatar_url');
            $user->hash = $hash;
            $user->avatar_file = $path;
        } else {
            $avatar = $request->input('avatar');
            $user->avatar_url = $avatar;
            if ($request->exists('hash')) {
                $ext = $request->exists('ext') ? $request->input('ext') : '.jpg';
                $ext = strpos($ext,'.') === 0 ? $ext : '.' . $ext;

                $hash = $request->input('hash');
                $path = $request->exists('path') ? $request->input('path') : $this->path . $hash . $ext;
                if (Storage::exists($path)) {
                    $user->hash = $hash;
                    $user->avatar_file = $path;
                }
            }
        }

        if ($user->save()) {
            $ret['message'] = trans('plus-illusts::messages.success');
            return $response->json($ret)->setStatusCode(201);
        }

        $ret['code'] = 500;
        $ret['message'] = trans('plus-illusts::messages.fail');
        return $response->json($ret);
    }

    public function destroy(Request $request, ResponseFactoryContract $response, PixivUser $user)
    {
        Storage::delete($user->avatar_file);
        if ($user->delete()) {
            return $response->json([
                'code' => 0,
                'message' => trans('plus-illusts::messages.success'),
                'userid' => $user->id,
            ]);
        }

        return $response->json([
            'code' => 500,
            'message' => trans('plus-illusts::messages.fail'),
            'userid' => $user->id,
        ]);
    }

    public function update(Request $request, ResponseFactoryContract $response, PixivUser $user)
    {
        if ($user->update($request->all(['name', 'userid', 'avatar']))) {
            return $response->json([
                'code' => 0,
                'message' => trans('plus-illusts::messages.success'),
                'userid' => $user->id,
            ]);
        }

        return $response->json([
            'code' => 500,
            'message' => trans('plus-illusts::messages.fail'),
            'userid' => $user->id,
        ]);
    }
}