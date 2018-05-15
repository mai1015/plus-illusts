<?php
/**
 * Created by PhpStorm.
 * User: mai1015
 * Date: 2018-04-10
 * Time: 11:58
 */

namespace Mai1015\PlusIllusts\API\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Mai1015\PlusIllusts\Models\Illust;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Zhiyi\Plus\Models\Tag;

class TagController
{

    protected $cate;

    /**
     * Create the Controller.
     *
     * @param \Illuminate\Contracts\Config\Repository $config
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(ConfigRepository $config)
    {
        //$this->config = $config;
        $this->cate = (int)$config->get('plus-illusts.tag_category');
    }

    public function show(Request $request)
    {
        $tags = Tag::whereIn('name',
            is_array($request->input('tags')) ? $request->input('tags') : explode(',', $request->input('tags')))->get();
        return $tags;
    }

    public function create(Request $request, ResponseFactoryContract $response)
    {
        $tags = is_array($request->input('tags')) ? $request->input('tags') : explode(',', $request->input('tags'));
        $result = [];
        foreach ($tags as $tag) {
            $t = Tag::where('name', '=' , $tag)->first();
            if (!$t) {
                $t = new Tag();
                $t->name = $tag;
                $t->weight = 0;
                $t->tag_category_id = $this->cate;
                $t->save();
            }
            $result[] = $t->id;
        }

        return $response->json($result)->setStatusCode(201);
    }

    public function store(Request $request, ResponseFactoryContract $response, Illust $model)
    {
        $tags = Tag::whereIn('id', is_array($request->input('tags')) ? $request->input('tags') : explode(',', $request->input('tags')))->get();
        if (! $tags) {
            return $response->json(['message' => ['填写的标签不存在或已删除']], 422);
        }

        $model->tags()->attach($tags);
        return $response->json([
            'message' => trans('plus-illusts::messages.success'),
        ]);
    }

    public function destroy(Request $request, ResponseFactoryContract $response, Illust $model)
    {
        $tags = Tag::whereIn('id', is_array($request->input('tags')) ? $request->input('tags') : explode(',', $request->input('tags')))->get();
        if ($tags) {
            $model->tags()->detach($tags);
            // return $response->json(['message' => ['填写的标签不存在或已删除']], 422);
        }

        return $response->json([
            'message' => trans('plus-illusts::messages.success'),
        ]);
    }
}