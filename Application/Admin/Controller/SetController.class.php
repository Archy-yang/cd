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
    const WEIXIN_QRCODE= 3;
    const WEIBO_QRCODE= 4;

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
                $tmp = array();
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

    public function qrcode()
    {
        $model = new Model();

        $list = $model->table('image')
            ->where(array(
                'type' => array('in', array(self::WEIBO_QRCODE, self::WEIXIN_QRCODE)),
            ))
            ->select();

        $weibo = '';
        $weixin = '';

        foreach ($list as $v) {
            if ($v['type'] == self::WEIBO_QRCODE) {
                $weibo = $v;
            }
            if ($v['type'] == self::WEIXIN_QRCODE) {
                $weixin = $v;
            }
        }

        $this->assign('weibo', $weibo);
        $this->assign('weixin', $weixin);

        $this->display();
    }

    public function saveQrcode()
    {
        $model = new Model();

        $delRe = $model->table('image')
            ->where(array(
                'type' => array('in', array(self::WEIBO_QRCODE, self::WEIXIN_QRCODE)),
            ))
            ->delete();
        if (false !== $delRe) {
            $data = array();

            if ($_POST['weibo_path'] && $_POST['weibo_name']) {
                $data[] = array(
                    'type' => self::WEIBO_QRCODE,
                    'path' => trim($_POST['weibo_path']),
                    'name' => trim($_POST['weibo_name']),
                    'create_time' => date('Y-m-d H:i:s'),
                );
            }
            if ($_POST['weixin_path'] && $_POST['weixin_name']) {
                $data[] = array(
                    'type' => self::WEIXIN_QRCODE,
                    'path' => trim($_POST['weixin_path']),
                    'name' => trim($_POST['weixin_name']),
                    'create_time' => date('Y-m-d H:i:s'),
                );
            }

            $saveRe = $model->table('image')
                ->addAll($data);

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
