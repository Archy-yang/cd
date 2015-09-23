<?php

namespace Home\Controller;

/**
 * @author archy
 */
class TransitionController extends HomeController 
{
    public function showList()
    {   
        $num = 15;

        $transition = M('transition');
        $count = $transition->where(array('is_delete' => 0))->count();

        $page = new Page($count, $num);
        $img = M('image')->where(array('type' => 8))->find();
        $banner = $img['path'].$img['name'];

        $show = $page->show();

        $list = $transition->where(array('is_delete' => 0))
            ->limit($page->firstRow, $page->listRows)
            ->select();

        $this->assign('list', $list);
        $this->assign('show', $show);
        $this->assign('banner', $banner);
        $this->display();
    }

    public function detail($id)
    {
        $transition = M('transition');

        $detail = $transition->where(array('id' => $id))->find();
        $img = M('image')->where(array('type' => 1))->find();

        $this->assign('img', $img);
        $this->assign('detail', $detail);

        $this->display();
    }
}
