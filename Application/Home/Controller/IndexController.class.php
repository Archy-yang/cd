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
    const PROJECT_INIT_NUM = 4;

    public function index()
    {
        $img = M('image')->where(array('type' => array('in', array(1,2))))->select();

        $adv = '';
        $banners = array();
        foreach ($img as $v) {
            if ($v['type'] == 1) {
                $adv = $v['path'].$v['name'];

                continue;
            }

            if ($v['type'] == 2) {
                $banners[] = $v['path'].$v['name'];
            }
        }

        list($project, $more) = $this->projectList(0, self::PROJECT_INIT_NUM);

        $this->assign('adv', $adv);
        $this->assign('banners', $banners);
        $this->assign('project', $project);
        $this->assign('more', $more);
                 
        $this->display();
    }

    public function moreProject($page = 1, $num = 10) 
    {
        if ($page = 1) {
            $skip = $page * self::PROJECT_INIT_NUM;
        } else {
            $skip = $page * $num;
        }

        list($projectList, $more) = $this->projectList($skip, $num);

        $this->assign('projectList', $projectList);
        $this->assign('more', $more);

        $this->display();
    }

    protected function projectList($skip, $num)
    {
        $projectList = M('project')->where(array(
            'is_delete' => 0,
        ))
        ->limit($skip, $num +1)
        ->select();


        if (count($projectList) > $num) {
            $more = true;
        } else {
            $more = false;
        }

        return array($projectList, $more);
    }

}
