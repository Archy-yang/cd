<?php

namespace Admin\Controller;

use Think\Page;
/**
 * @author archy
 */
class TransitionController extends AdminController
{
    /**
     * 转型列表
     *
     * @author archy
     */
    public function transitionList()
    {
        $transition = M("transition");

        $count = $transition->where(array('is_delete' => 0))->count();

        $page = new Page($count, 25);
        $show = $page->show();

        $list = $transition->where(array('is_delete' => 0))
            ->order($page->firstRow, $page->listRows)
            ->select();

        $this->assign("list", $list);
        $this->assign("show", $show);
        $this->display();
    }

    /**
     * 添加转型
     *
     * @author archy
     */
    public function addTransition()
    {
        $this->display();
    }

    /**
     * 保存转型
     *
     * @author archy
     */
    public function saveTransition()
    {
        $transition = D('Transition');

        $data = $transition->create();

        if ($data) {
            $tag = $data['tag'];

            $data['tag'] = json_encode(explode(' ', $tag));

            $result = $transition->data($data)->add();
            if ($result) {
                echo json_encode(array(
                    'code' => 0,
                    'msg' => ''
                ));

                return ;
            }
        }

        echo json_encode(array(
            'code' => 1,
            'msg' => '',
        ));

        return ;
    
    }

    /**
     * 编辑转型
     *
     * @author archy
     */
    public function editTransition($id)
    {
        $transition = M('transition');

        $info = $transition->where(array("id" => $id))
            ->find();

        $this->assign('info', $info);

        $this->display();
    }

    /**
     * 更新转型
     *
     * @author archy
     */
    public function updateTransition()
    {
        $transition = D('Transition');

        $data = $transition->create();

        if ($data) {
            $id = $data['id'];

            if ($id > 0) {
                unset($data['id']);
                $tag = $data['tag'];

                $data['tag'] = json_encode(explode(' ', $tag));

                $result = $transition->where(array('id' => $id))->data($data)->save();

                if (false !== $result) {
                    echo json_encode(array(
                        'code' => 0,
                        'msg' => ''
                    ));

                    return ;
                }
            }
        }

        echo json_encode(array(
            'code' => 1,
            'msg' => '',
        ));

        return ;
    }

    /**
     * 删除转型
     *
     * @author archy
     */
    public function deleteTransition($id)
    {
        $transition = M('transition');

        $result = $transition->where(array("id" => intval($id)))
            ->data(array('is_delete' => 1))
            ->save();

        if (false !== $result) {
            echo json_encode(array(
                'code' => 0,
                'msg' => '',
            ));

            return ;
        }

        echo json_encode(array(
            'code' => 1,
            'msg' => '',
        ));

        return ;
    }

    public function setTop($id, $type = 1)
    {
        $transition = M('transition');

        $result = $transition->where(array("id" => intval($id)))
            ->data(array(
                'top' => $type,
                'top_time' => date('Y-m-d H:i:s'),
            ))
            ->save();

        if (false !== $result) {
            echo json_encode(array(
                'code' => 0,
                'msg' => '',
            ));

            return ;
        }

        echo json_encode(array(
            'code' => 1,
            'msg' => '',
        ));

        return ;
    
    }
}
