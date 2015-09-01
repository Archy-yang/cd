<?php
namespace Admin\Controller;

use Think\Model;

/**
 * 后台内容控制器
 * @author archy
 */
class SetController extends AdminController
{
    const TOP_IMG = 1;
    const CAROUSEL_IMG = 2;

    public function topImage()
    {
        $model = new Model();
        $img = $model->table('image')
            ->where(array(
                'type' => self::TOP_IMG,
            ))
            ->order('id desc')
            ->find();
        if (!$img) {
            $img = array(
                'path' => '',
                'name' => '',
            );
        }

        $this->assign('img', $img);

        $this->display();
    }

    public function carousel()
    {
        $model = new Model();

        $img = $model->table('image')
            ->where(array(
                'type' => self::CAROUSEL_IMG,
            ))
            ->order('id')
            ->select();

        $this->assign('list', $img);

        $this->display();
    }

    public function saveCarousel()
    {
        $model = new Model();

        $delRe = $model->table('image')
            ->where(array(
                'type' => self::CAROUSEL_IMG,
            ))
            ->delete();

        if (false !== $delRe) {
            $data = array();

            foreach ($_POST['img'] as $v) {
                $tmp = [];
                list($tmp['name'], $tmp['path']) = explode('|', $v);
                $tmp['type'] = self::CAROUSEL_IMG;
                $tmp['create_time'] = date('Y-m-d H:i:s');

                $data[] = $tmp;
            }

            $saveRe = $model->table('image')
                ->addall($data);

            if ($saveRe) {
                echo json_encode(array(
                    'code' => 0,
                    'msg' => '',
                ));

                return ;
            }
        } 
        
        echo json_encode(array(
            'code' => 1,
            'msg' => '',
        ));

        return;
    }

    /**
     * 保存顶部图片
     *
     * @author archy
     */
    public function saveTopAdv()
    {
        $model = new Model();

        $delRe = $model->table('image')
            ->where(array(
                'type' => 1,
            ))
            ->delete();
        if (false !== $delRe) {
            $saveRe = $model->table('image')
                ->data(array(
                    'type' => 1,
                    'path' => trim($_POST['path']),
                    'name' => trim($_POST['name']),
                    'create_time' => date('Y-m-d H:i:s'),
                ))
                ->add();

            if ($saveRe) {
                echo json_encode(array(
                    'code' => 0,
                    'msg' => '',
                ));

                return ;
            }
        } 
        
        echo json_encode(array(
            'code' => 1,
            'msg' => '',
        ));

        return;
    }
}
