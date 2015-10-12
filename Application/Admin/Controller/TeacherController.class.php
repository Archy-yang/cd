<?php

namespace Admin\Controller;

use Think\Page;
/**
 * @author archy
 */
class TeacherController extends AdminController
{
    public function getList()
    {
        $count = M('teacher')->count();

        $page = new Page($count, 25);
        $show = $page->show();

        $list = M('teacher')->limit($page->firstRow, $page->listRows)
            ->select();

        $this->assign('list', $list);
        $this->assign('show', $show);

        $this->display();
    }

    public function add()
    {
        $this->display();
    }

    public function save()
    {
        $teacher = D('Teacher');

        $data = $teacher->create();

        if ($data) {
            $result = $teacher->add($data);

            if ($result) {
                echo json_encode(array(
                    'code' => 0,
                    'msg' => '',
                ));

                return ;
            }
        } else {
            $error = $teacher->getError();
        }

        echo json_encode(array(
            'code' => 1,
            'msg' => isset($error) ? $error : '添加失败',
        ));
        return;
    }
}
