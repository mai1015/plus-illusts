<?php

declare(strict_types=1);

namespace Mai1015\PlusIllusts\Admin\Controllers;

use Mai1015\PlusIllusts\Models\Illust;
use Mai1015\PlusIllusts\Models\IllustFile;
use Mai1015\PlusIllusts\Models\PixivUser;

class HomeController
{
    public function index()
    {
        return response()->json([
            'user' => PixivUser::count(),
            'illusts' => Illust::count(),
            'illustsFile' => IllustFile::count(),
        ]);
        //return trans('plus-illusts::messages.success');
    }
}
