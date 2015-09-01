<?php

namespace Admin\Controller;

use Think\Page;
/**
 * @author archy
 */
class InverstorController extends AdminController
{
    public function inverstor()
    {
        $inverstor = M('inverstor');

        $info = $inverstor->where(array('is_show' => 1))->find();

        if (isset($info['company_creation_time']) && !$info['company_creation_time']) {
            $info['company_creation_time'] = date("Y");
        }

        $this->assign('info', $info);
        $this->display();
    }

    public function updateInverstor()
    {
        $inverstor = D('Inverstor');

        $data = $inverstor->create();

        if ($data) {
            trace($data);
            $data['is_show'] = 1;

            if (!isset($data['id'])) {
                $result = $inverstor->data($data)->add();
            } else {
                $id = $data['id'];
                if ($id > 0) {
                    unset($data['id']);

                    $result = $inverstor->where(array('id' => $id))
                        ->data($data)
                        ->save();
                } else {
                    $result = false;
                }
            }

            if (false !== $result) {
                echo json_encode(array(
                    'code' => 0,
                    'msg' => '',
                ));

                return;
            }
        } 

        echo json_encode(array(
            'code' => 1,
            'msg' => '',
        ));

        return;
    }

    public function inverstorList()
    {
        $inverstor = M('inverstor');

        $count = $inverstor->where(array('is_sign_up' => 1))->count();

        $page = new Page($count, 25);
        $show = $page->show();

        $list = $inverstor->where(array('is_sign_up' => 1))
            ->limit($page->firstRow, $page->listRows)
            ->select();

        $this->assign('list', $list);
        $this->assign('show', $show);

        $this->display();
    }

    public function inverstorInfo($id)
    {
        $inverstor = M('inverstor');

        $info = $inverstor->where(array("id" => $id))
            ->find();

        $this->assign('info', $info);

        $this->display();
    }
}
