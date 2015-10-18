<?php

namespace Admin\Controller;

/**
 * @author archy
 */
class InfoController extends AdminController
{
    public function home()
    {
        $info = $this->getInfo(array("home"));

        $this->assign("info", $info);
        $this->display();
    }

    public function saveHome()
    {
        $content = $_POST['home'];
        $id = $_POST['id'];

        $data = array(
            'home' => $content,
        );

        if (false !== $this->saveInfo($data, $id)) {
            echo json_encode(array(
                'code' => 0,
                'msg' => '',
            ));
            
            return;
        }

        echo json_encode(array(
            'code' => 1,
            'msg' => '',
        ));
        
        return;
    }

    public function zhao()
    {
        $info = $this->getInfo(array("zhao"));

        $this->assign("info", $info);
        $this->display();
    }

    public function saveZhao()
    {
        $content = $_POST['zhao'];
        $id = $_POST['id'];

        $data = array(
            'zhao' => $content,
        );

        if (false !== $this->saveInfo($data, $id)) {
            echo json_encode(array(
                'code' => 0,
                'msg' => '',
            ));
            
            return;
        }

        echo json_encode(array(
            'code' => 1,
            'msg' => '',
        ));
        
        return;
    }

    public function contect()
    {
        $info = $this->getInfo(array("contect"));

        $this->assign("info", $info);
        $this->display();
    }

    public function saveContect()
    {
        $content = $_POST['contect'];
        $id = $_POST['id'];

        $data = array(
            'contect' => $content,
        );

        if ($false !== $this->saveInfo($data, $id)) {
            echo json_encode(array(
                'code' => 0,
                'msg' => '',
            ));
            
            return;
        }

        echo json_encode(array(
            'code' => 1,
            'msg' => '',
        ));
        
        return;
    }

    public function relief()
    {
        $info = $this->getInfo(array("relief"));

        $this->assign("info", $info);
        $this->display();
    }

    public function saveRelief()
    {
        $content = $_POST['relief'];
        $id = $_POST['id'];

        $data = array(
            'relief' => $content,
        );
        if (false !== $this->saveInfo($data, $id)) {
            echo json_encode(array(
                'code' => 0,
                'msg' => '',
            ));
            
            return;
        }

        echo json_encode(array(
            'code' => 1,
            'msg' => '',
        ));
        
        return;
    }

    protected function  getInfo(array $field)
    {
        $field[] = 'id';
        $info = M('info');

        $info = $info->field($field)
            ->find();

        return $info;
    }

    protected function saveInfo(array $data, $id)
    {
        if ($id) {
            return M("info")->where(array('id' => $id))->data($data)->save();
        } else {
            return M('info')->data($data)->add();
        }
    }
}
