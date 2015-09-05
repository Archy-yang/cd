<?php

namespace Home\Controller;

/**
 * @author archy
 */
class CollegeController extends HomeController 
{
    public function showList()
    {
        $num = 15;

        $college = M('college');
        $count = $college->where(array('is_delete' => 0))->count();

        $page = new Page($count, $num);
        $img = M('image')->where(array('type' => 1))->find();

        $show = $page->show();

        $list = $college->where(array('is_delete' => 0))
            ->limit($page->firstRow, $page->listRows)
            ->select();

        $this->assign('list', $list);
        $this->assign('show', $show);
        $this->assign('img', $img);
        $this->display();
    
    }

    public function detail($id)
    {
        $img = M('image')->where(array('type' => 1))->find();

        $college = M('college');
        $detail = $college->where(array('id' => $id))->find();

        $this->assign('img', $img);
        $this->assign('detail', $detail);
        
        $this->display();
    }
}
