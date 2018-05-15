<?php
/**
 * Created by PhpStorm.
 * User: mai1015
 * Date: 2018-04-09
 * Time: 22:55
 */

namespace Mai1015\PlusIllusts\API\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Illuminate\Support\Facades\Storage;
use Mai1015\PlusIllusts\Models\Illust;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Mai1015\PlusIllusts\Models\IllustFile;

class IllustController
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
        $this->path = $config->get('plus-illusts.image_path');
    }

    public function index(Request $request, ResponseFactoryContract $response, Illust $model) {
        $item = (int)$request->query('item');
        return $model->with(['files','tags'])->paginate($item);
    }

    public function store(Request $request, ResponseFactoryContract $response) {
        $workid = (int)$request->input('workid');
        if (Illust::find($workid)){
            return $response->json(['code'=>201, 'message'=>'dupulicated']);
        }

        $title = $request->input('title');
        $caption = $request->input('caption');
        $pixivid = (int)$request->input('pixivid');
        $thumb = $request->input('thumbnail');

        $illust = new Illust();
        $illust->id = $workid;
        $illust->title = $title;
        $illust->caption = $caption;
        $illust->pixiv_id = $pixivid;
        $illust->thumbnail = $thumb;

        if ($illust->save()) {
            return $response->json([
                'code' => 201,
                'message' => trans('plus-illusts::messages.success'),
            ],201);
        }

        return $response->json([
            'code' => 500,
            'message' => trans('plus-illusts::messages.fail'),
        ]);
    }

    public function update(Request $request, ResponseFactoryContract $response, Illust $model)
    {
        if ($model->update($request->all(['title', 'caption', 'thumbnail']))){
            return $response->json([
                'code' => 0,
                'message' => trans('plus-illusts::messages.success'),
                'userid' => $model->id,
            ]);
        }

        return $response->json([
            'code' => 500,
            'message' => trans('plus-illusts::messages.fail'),
            'userid' => $model->id,
        ]);
    }

    public function destroy(Request $request, ResponseFactoryContract $response, Illust $model) {
        if ($model->delete()){
            return $response->json([
                'code' => 0,
                'message' => trans('plus-illusts::messages.success'),
                'userid' => $model->id,
            ]);
        }

        return $response->json([
            'code' => 500,
            'message' => trans('plus-illusts::messages.fail'),
            'userid' => $model->id,
        ]);
    }

    public function show(Request $request, ResponseFactoryContract $response, Illust $model)
    {
        // TODO: create model with all files
        return $model;
    }

    public function store_file(Request $request, ResponseFactoryContract $response, Illust $model)
    {
        if ($request->exists('hash')) {
            $hash = $request->input('hash');
            $path = null;
            $width = 0;
            $height = 0;
            $origin_filename = null;
            if (!IllustFile::hash($hash)->get()) {
                return $response->json([
                    'code' => 0,
                    'message' => trans('plus-illusts::messages.dupulicated'),
                ]);
            }
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $origin_filename = $file->getClientOriginalName();
                $ext = $file->getClientOriginalExtension();
                list($width, $height) = getimagesize($request->file('image'));
                $path = $file->storeAs($this->path, $hash.'.'.$ext);
            } else {
                // Get ext
                $ext = $request->exists('ext') ? $request->input('ext') : '.jpg';
                $ext = strpos($ext,'.') === 0 ? $ext : '.' . $ext;
                // guess path if not found
                $origin_filename = $hash.$ext;
                $path = $request->exists('path') ? $request->input('path') : $this->path . $origin_filename;
                if (Storage::exists($path)) {
                    $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
                    list($width, $height) = getimagesize($storagePath.$path);
                } else {
                    return $response->json([
                        'code' => 404,
                        'message' => 'not found',
                        'path' => $path,
                    ]);
                }
            }
            if ($path) {
                $illust_file = new IllustFile();
                $illust_file->illust_id = $model->id;
                $illust_file->hash = $hash;
                $illust_file->filename = $path;
                $illust_file->origin_filename = $origin_filename;
                $illust_file->width = $width;
                $illust_file->height = $height;
                if ($illust_file->save()) {
                    return $response->json([
                        'code' => 201,
                        'message' => trans('plus-illusts::messages.success'),
                        'workid' => $model->id,
                    ])->setStatusCode(201);
                }
            }
        }
        return $response->json($request->all());
    }

    public function delete_file(Request $request, ResponseFactoryContract $response, Illust $model)
    {
        if ($request->exists('hash')) {
            $hash = $request->input('hash');
            $file = IllustFile::hash($hash)->first();
            if (Storage::delete($file->filename)) {
                $file->delete();
                return $response->json([
                    'code' => 200,
                    'message' => trans('plus-illusts::messages.success'),
                    'workid' => $model->id,
                ]);
            }

            return $response->json([
                'code' => 500,
                'message' => trans('plus-illusts::messages.fail'),
                'workid' => $model->id,
            ]);
        }
    }
}