<?php
/**
 * Created by PhpStorm.
 * User: maryan.espinoza
 * Date: 10/10/2019
 * Time: 10:44
 */

class git_version
{
    public static  function get()
    {
        return sprintf('UNIMARK S.A © %s %s', date('Y'), env('APP_VERSION'));
        //return sprintf('UNIMARK S.A © %s V%s.%s.%s', date('Y'), self::MAJOR, self::MINOR, self::getGitVersion());
    }
}

