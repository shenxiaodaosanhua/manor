<?php


namespace App\Admin\Extensions\Nav;


/**
 * Class ClearCache
 * @package App\Admin\Extensions\Nav
 */
class ClearCache
{
    /**
     * @return string
     */
    public function __toString()
    {
        $cacheUrl = route('admin.clear.cache');
        return <<<HTML

<li>
    <a href="{$cacheUrl}" title="清除缓存">
      <i class="fa fa-rotate-left"></i>
    </a>
</li>
HTML;
    }
}
