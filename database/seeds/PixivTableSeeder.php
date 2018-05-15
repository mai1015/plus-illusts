<?php
namespace Mai1015\PlusIllusts\Seeds;

use Illuminate\Database\Seeder;
use Mai1015\PlusIllusts\Models\PixivUser;

class PixivTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $pixiv = new PixivUser;
        $pixiv->name = "mai1015";
        $pixiv->id = 21444791;
        $pixiv->status = 0;
        $pixiv->avatar = "https://source.pixiv.net/common/images/no_profile.png";
        $pixiv->save();
    }
}
