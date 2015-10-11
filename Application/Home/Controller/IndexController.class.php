<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use OT\DataDictionary;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends HomeController 
{
    const PROJECT_INIT_NUM = 5;

    public function index()
    {
        $img = M('image')->where(array('type' => array('in', array(1, 2, 6))))->select();

        $adv = '';
        $carousel = array();
        $banner = '';
        foreach ($img as $v) {
            switch ($v['type']) {
                case 1:
                    $adv = $v['path'].$v['name'];

                    break;
                case 2:
                    $carousel[] = $v['path'].$v['name'];

                    break;
                case 6:
                    $banner = $v['path'].$v['name'];

                    break;

            }
        }

        list($project, $more) = $this->projectList(0, self::PROJECT_INIT_NUM);

        $this->assign('adv', $adv);
        $this->assign('carousel', $carousel);
        $this->assign('banner', $banner);
        $this->assign('project', $project);
        $this->assign('more', $more);
                 
        $this->display();
    }

    public function moreProject($page = 1, $num = 10) 
    {
        $skip = ($page-1) * $num + self::PROJECT_INIT_NUM;

        list($projectList, $more) = $this->projectList($skip, $num);

        $this->assign('project', $projectList);

        $html = $this->fetch();

        echo json_encode(array(
            'code' => 0,
            'more' => $more,
            'html' => $html,
        ));
    }

    protected function projectList($skip, $num)
    {
        $projectList = M('project')->where(array(
            'is_delete' => 0,
            'is_index' => 1,
            'is_pass' => 1,
        ))
        ->order('index_sort desc, id desc')
        ->limit($skip, $num +1)
        ->select();


        if (count($projectList) > $num) {
            array_pop($projectList);
            $more = true;
        } else {
            $more = false;
        }

        return array($projectList, $more);
    }

}
