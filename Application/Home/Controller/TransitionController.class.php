<?php

namespace Home\Controller;

/**
 * @author archy
 */
class TransitionController extends HomeController 
{
    public function showList()
    {
        $img = M('image')->where(array('type' => 1))->find();

        $this->assign('img', $img);
        $this->display();
    }

    protected function getList()
    {
    
    }
}
