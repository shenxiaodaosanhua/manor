<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PushTemplates extends Model
{
    use SoftDeletes;

    protected $table = 'push_template';

    const MODULE_TYPE_MANOR_VALUE                = 1;
    const MODULE_TYPE_RED_ENVELOPE_SING_IN_VALUE = 2;

    const MODULE_TYPES = [
        self::MODULE_TYPE_MANOR_VALUE                => '庄园',
        self::MODULE_TYPE_RED_ENVELOPE_SING_IN_VALUE => '红包签到'
    ];

    const PUSH_TYPE_SITE_VALUE      = 1;
    const PUSH_TYPE_PUSH_VALUE      = 2;
    const PUSH_TYPE_SITE_PUSH_VALUE = 3;

    const PUSH_TYPES = [
        self::PUSH_TYPE_SITE_VALUE      => '站内',
        self::PUSH_TYPE_PUSH_VALUE      => 'push',
        self::PUSH_TYPE_SITE_PUSH_VALUE => '站内+push',
    ];

    const PUSH_GROUP_TYPE_SINGLE = 1;
    const PUSH_GROUP_TYPES       = [
        self::PUSH_GROUP_TYPE_SINGLE => '单点推送'
    ];

    const MODULE_TYPE_FIELD = 'module_type';
    const SCENE_CODE_FIELD = 'scene_code';
    const TITLE_FIELD = 'title';
    const CONTENT_FIELD = 'content';
    const SCENE_DESC_FIELD = 'scene_desc';
    const PUSH_TYPE = 'push_type';
    const PUSH_GROUP_TYPE = 'push_group_type';
    const JUMP_URL_FIELD = 'jump_url';

    /**
     * @param string $scene_code
     * @return mixed
     * @author sunshine
     */
    public static function pushTemplateBySceneCode(string $scene_code)
    {
        return self::where(self::SCENE_CODE_FIELD,$scene_code)->first();
    }
}
