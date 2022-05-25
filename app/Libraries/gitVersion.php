<?php
/**
 * Created by PhpStorm.
 * User: maryan.espinoza
 * Date: 10/10/2019
 * Time: 10:44
 */

class git_version
{
    const MAJOR = 1;
    const MINOR = 0;

    public static  function get()
    {
        $commitHash = trim(exec('git rev-list --all --count'));
        return sprintf('UNIMARK S.A © %s V%s.%s.%s', date('Y'),self::MAJOR, self::MINOR,  $commitHash);
    }
}
