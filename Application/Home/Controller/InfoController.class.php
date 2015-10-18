<?php

namespace Home\Controller;

/**
 * @author archy
 */
class InfoController extends HomeController
{
    protected $type = array('home', 'zhao', 'contect', 'relief');

    public function info($type)
    {
        if (in_array($type, $this->type)) {
            $this->assign('content', $this->info[$type]);
        }

        $this->display();
    }
}
