<?php

namespace System\Base;

class Request
{
    static public function isPost()
    {
        $isPost = false ;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isPost = true;
        }

        return $isPost;
    }

    static public function isAjax()
    {
        $isAjax = false ;

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
        ) {
            $isAjax = true ;
        }

        return $isAjax;
    }
}