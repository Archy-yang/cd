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

        $img = M('image')->where(array('type' => 5))->select();

        $carousel = array();
        foreach ($img as $v) {
            $carousel[] = $v['path'].$v['name'];
        }

        $show = $page->show();

        $list = $college->where(array('is_delete' => 0))
            ->limit($page->firstRow, $page->listRows)
            ->select();

        $this->assign('list', $list);
        $this->assign('show', $show);
        $this->assign('carousel', $carousel);
        $this->display();
    
    }

    public function detail($id)
    {
        $img = M('image')->where(array('type' => 7))->find();
        $banner = $img['path'].$img['name'];

        $college = M('college');
        $detail = $college->where(array('id' => $id))->find();

        $this->assign('banner', $banner);
        $this->assign('detail', $detail);
        
        $this->display();
    }

    public function signUp()
    {
        $signUp = D('CollegeSignUp');

        $data = $signUp->create();

        if ($data) {
            $result = $signUp->data($data)->add();

            if ($result) {
                echo json_encode(array(
                    'code' => 0,
                ));

                return ;
            }
            $msg = '报名失败';
        } else {
            $msg = $signUp->getError();
        }

        echo json_encode(array(
            'code' => 1,
            'msg' => $msg,
        ));
    }
}
