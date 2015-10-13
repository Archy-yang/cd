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

    public function edit($id)
    {
        $teacher = M('teacher');

        $info = $teacher->where(array('id' => $id))
            ->find();

        $this->assign("info", $info);
        $this->display();
    }

    public function update()
    {
        $teacher = D('Teacher');

        $data = $teacher->create();

        if ($data) {
            $id = $data['id'];
            unset($data['id']);

            $result = $teacher->where(array('id' => $id))->save($data);

            if (false !== $result) {
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
            'msg' => isset($error) ? $error : '保存失败',
        ));
        return;
    }
}
