<?php

namespace Home\Controller;

/**
 * @author archy
 */
class ProjectController extends HomeController
{
    public function projectList()
    {
        $img = M('image')->where(array('type' => 9))->find();
        $banner = $img['path'].$img['name'];

        list($project, $more) = $this->getList(0, 5);

        $this->assign('banner', $banner);
        $this->assign('project', $project);
        $this->assign('more', $more);
        $this->display();
    }

    public function info()
    {
        $img = M('image')->where(array('type' => 1))->find();

        $info = M('inverstor')->where(array(
            'is_show' => 1,
            'is_sign_up' => 0,
        ))
        ->find();

        $info['resource'] = explode("\n", $info['resource']);
        $info['need'] = explode("\n", $info['need']);

        $this->assign('img', $img);
        $this->assign('info', $info);
        $this->display();
    }

    public function create()
    {
        $img = M('image')->where(array('type' => 1))->find();

        $this->assign('img', $img);
        $this->display();
    }

    public function saveCreate()
    {
        $inverstor = D('Inverstor');

        $data = $inverstor->create();

        $flag = false;
        if ($data) {
            $flag = $inverstor->data($data)->add();
        }

        if ($flag) {
            $this->success('申请成功', U('Project/projectList'));
        } else {
            $this->error('申请失败');
        }
    }

    protected function getList($skip, $num)
    {
        $projectList = M('project')->where(array(
            'is_delete' => 0,
            'is_pass' => 1,
        ))
        ->order('pass_time desc')
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
