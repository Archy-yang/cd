<?php

namespace Admin\Controller;

use Think\Page;
/**
 * @author archy
 */
class CollegeController extends AdminController
{
    public function collegeList()
    {
        $college = M("college");
        
        $count = $college->where(array("is_delete" => 0))->count();

        $page = new Page($count, 25);

        $show = $page->show();

        $list = $college->where(array('is_delete' => 0))
            ->limit($page->firstRow, $page->listRows)
            ->select();

        $this->assign('show', $show);
        $this->assign('list', $list);

        $this->display();
    }

    public function addCollege()
    {
        $this->display();
    }
    
    public function saveCollege()
    {
        $college = D("College");

        $data = $college->create();

        if ($data) {
            $result = $college->data($data)->add();
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

    public function editCollege($id)
    {
        $college = M('college');

        $info = $college->where(array("id" => $id))
            ->find();

        $this->assign('info', $info);

        $this->display();
    }

    public function updateCollege()
    {
        $college = D('College');

        $data = $college->create();

        if ($data) {
            $id = $data['id'];

            if ($id > 0) {
                unset($data['id']);

                $result = $college->where(array('id' => $id))->data($data)->save();

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

    public function deleteCollege($id)
    {
        $college = M('college');

        $result = $college->where(array("id" => intval($id)))
            ->data(array(
                'is_delete' => 1,
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

    public function signUpList($id)
    {
        $collegeSignUp = M('college_sign_up');

        $count = $collegeSignUp->where(array('college_id' => $id))->count();
        $page = new Page($count, 25);
        $show = $page->show();

        $list = $collegeSignUp->where(array('college_id' => $id))
            ->limit($page->firstRow, $page->listRows)
            ->select();

        $this->assign('list', $list);
        $this->assign('show', $show);
        $this->display();
    }
}
