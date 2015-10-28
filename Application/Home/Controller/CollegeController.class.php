<?php

namespace Home\Controller;

/**
 * @author archy
 */
class CollegeController extends HomeController 
{
    const NUM = 15;

    public function showList()
    {
        $college = M('college');

        $comming = $this->getComming($college);
        $now = $this->getNow($college);
        $pass = $this->getPass($college);
        list($more, $list) = $this->getList($college);

        $img = M('image')->where(array('type' => 5))->select(); 
        $carousel = array();
        foreach ($img as $v) {
            $carousel[] = $v['path'].$v['name'];
        }

        $this->assign('comming', $comming);
        $this->assign('now', $now);
        $this->assign('pass', $pass);
        $this->assign('carousel', $carousel);
        $this->assign('list', $list);
        $this->assign('more', $more);
        $this->assign('title', '虫洞商学院');
        $this->display();
    
    }

    public function moreList($page)
    {
        $model = M('college');

        $skip = $page * self::NUM + 2;

        list($more, $list) = $this->getList($model, $skip);


        $this->assign('list', $list);
        $html = $this->fetch();

        echo json_encode(array(
            'code' => 0,
            'msg' => '',
            'more' => $more,
            'html' => $html,
        ));
        
        return;
    }

    public function detail($id)
    {
        $img = M('image')->where(array('type' => 7))->find();
        $banner = $img['path'].$img['name'];

        $college = M('college');
        $detail = $college->where(array('id' => $id))->find();

        if ($detail) {
            preg_match_all('/<p>([^<]+)</', $detail['schedule'], $match);

            $detail['schedule'] = $match[1];

            $teacher = M('teacher_college')->alias('tc')
                ->join('teacher as t on t.id = tc.teacher_id')
                ->where(array('tc.college_id' => $id))
                ->order('is_main desc, t.id desc')
                ->select();

            foreach ($teacher as $key => $v) {
                preg_match_all('/<p>([^<]+)</', $v['desc'], $match);

                $teacher[$key]['desc'] = $match[1];
            }

            $this->assign('teacher', $teacher);
            $this->assign('banner', $banner);
            $this->assign('detail', $detail);
        }

        $this->assign('title', $detail['title']);
        $this->display();
    }

    public function note($id)
    {
        $college = M('college');
        $detail = $college->where(array('id' => $id))->find();

        $this->assign('title', $detail['title']);
        $this->assign('detail', $detail);

        $this->display();
    
    }

    public function signUp()
    {
        $signUp = D('CollegeSignUp');

        $data = $signUp->create();

        if ($data) {
            $result = $signUp->data($data)->add();

            if ($result) {
                echo json_encode(array(
                    'code' => 0,
                ));

                return ;
            }
            $msg = '报名失败';
        } else {
            $msg = $signUp->getError();
        }

        echo json_encode(array(
            'code' => 1,
            'msg' => $msg,
        ));
    }

    protected function getComming($model)
    {
        $condition = array(
            'start_time' => array(
                'gt',
                date('Y-m-d H:i:s'),
            ),
        );

        $list = $this->getCollege($model, $condition);

        if ($list){
            $info = $list[0];
        } else {
            $info = array();
        }

        return $info;
    }

    protected function getNow($model)
    {
        $condition = array(
            'start_time' => array(
                'lt',
                date('Y-m-d H:i:s'),
            ),
            'end_time' => array(
                'gt',
                date('Y-m-d H:i:s'),
            ),
        );

        $list = $this->getCollege($model, $condition);

        if ($list){
            $info = $list[0];
        } else {
            $info = array();
        }

        return $info;
    
    }

    protected function getPass($model)
    {
        $condition = array(
            'end_time' => array(
                'lt',
                date('Y-m-d H:i:s'),
            ),
        );

        $list = $this->getCollege($model, $condition, 2);

        if (!$list){
            $list = array();
        }

        return $list;
    }

    protected function getList($model, $skip = 2, $num = self::NUM)
    {
        trace('!!!');
        $condition = array(
            'published' => 1,
            'is_delete' => 0,
            'end_time' => array(
                'lt',
                date('Y-m-d H:i:s'),
            ),
        );

        $list = $model->where($condition)
            ->order('published_time desc, id desc')
            ->limit($skip, $num+1)
            ->select();

        if ($list) {
            if (count($list) > $num) {
                $more = true;
                array_pop($list);
            } else {
                $more = false;
            }
        } else {
            $list = array();
            $more = false;
        }

        return array($more, $list);
    }

    protected function getCollege($model, array $condition, $limit = 1)
    {
        $condition['published'] = 1;
        $condition['is_delete'] = 0;

        $info = $model->where($condition)
            ->order('published_time desc, id desc')
            ->limit($limit)
            ->select();

        if ($info) {
            foreach ($info as $key => $v)  {
                $teacher = $model->table("teacher_college")
                    ->alias('tc')
                    ->join('teacher as t on t.id = tc.teacher_id')
                    ->where(array('tc.college_id' => $v['id']))
                    ->order('is_main desc')
                    ->select();

                if ($teacher) {
                    foreach ($teacher as $vo) {
                        $info[$key]['teacher'][] = $vo['name'];
                    }
                } else {
                    $info[$key]['teacher'] = array();
                }
            }
        }

        return $info;
    }
}
