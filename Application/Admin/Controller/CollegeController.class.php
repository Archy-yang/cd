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

    public function publish($id)
    {
        $link = M('teacher_college');

        $isLink = $link->where(array('college_id' => $id))->find();

        if ($isLink) {
            $college = M('college');

            $result = $college->where(array('id' => $id))
                ->data(array(
                    'published' => 1,
                    'published_time' => date('Y-m-d H:i:s'),
                ))
                ->save();

            if (false !== $result) {
                echo json_encode(array(
                    'code' => 0,
                    'msg' => '',
                ));
                
                return;
            }

            echo json_encode(array(
                'code' => 1,
                'msg' => '操作失败',
            ));
            
            return;
        }

        echo json_encode(array(
            'code' => 1,
            'msg' => '请选择讲师后再发布 ',
        ));
        
        return;
    }

    public function unpublish($id)
    {
        $college = M('college');

        $result = $college->where(array('id' => $id))
            ->data(array(
                'published' => 0,
            ))
            ->save();

        if (false !== $result) {
            echo json_encode(array(
                'code' => 0,
                'msg' => '',
            ));
            
            return;
        }

        echo json_encode(array(
            'code' => 1,
            'msg' => '操作失败',
        ));
        
        return;
    }

    public function teacherList($id)
    {
        $link = M('teacher_college');

        $list = $link->alias('tc')
            ->join('inner join teacher as t on t.id = tc.teacher_id')
            ->where(array('tc.college_id' => $id))
            ->select();

        $this->assign('list', $list);
        $this->assign('collegeId', $id);

        $this->display();
    }

    public function addTeacher($collegeId)
    {
        if ($collegeId) {
            $link = M("teacher_college");
            $linkList = $link->where(array('college_id' => $collegeId))
                ->select();

            if (false !== $linkList) {
                $linkTeacherId = array();

                if ($linkList) {
                    foreach($linkList as $v){
                        $linkTeacherId[] = $v['teacher_id'];
                    }
                }

                if (empty($linkTeacherId)) {
                    $where = array(
                        'id' => array('gt', 1),
                    );
                } else {
                    $where = array(
                        'id' => array('not in', $linkTeacherId),
                    );
                }

                $teacher = M('teacher'); 
                $teacherList = $teacher->where($where)
                    ->select();
                
                $this->assign('list', $teacherList);
            }
        }
        $this->assign('collegeId', $collegeId);
        $this->display();
    }

    public function saveTeacher($collegeId)
    {
        if ($collegeId > 0) {
            if (!empty($_POST['ids'])) {
                $ids = $_POST['ids'];
                $link = M('teacher_college');
                
                $data = array();
                foreach ($ids as $v) {
                    $data[] = array(
                        'teacher_id' => intval($v),
                        'college_id' => intval($collegeId),
                    );
                }

                $result = $link->addall($data);

                if ($result) {
                    echo json_encode(array(
                        'code' => 0,
                        'msg' => '',
                    ));
                    
                    return;
                }
            } else {
                echo json_encode(array(
                    'code' => 1,
                    'msg' => '未选择任何讲师',
                ));
                
                return;
            }
        }

        echo json_encode(array(
            'code' => 1,
            'msg' => '操作失败',
        ));
        
        return;
    }

    public function setMain($id, $collegeId)
    {
        if ($id > 0 && $collegeId) {
            $link = M('teacher_college');

            $result = $link->where(array('college_id' => $collegeId))
                ->data(array(
                    'is_main' => array('exp', 'if(teacher_id='.$id.', 1, 0)'),
                ))
                ->save();

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
            'msg' => '操作失败',
        ));
        
        return;
    }

    public function deleteTeacher($id, $collegeId)
    {
        if ($id > 0 && $collegeId) {
            $link = M('teacher_college');
            $where = array(
                'college_id' => $collegeId,
                'teacher_id' => $id,
            );

            $info = $link->where($where)->find();

            if ($info) {
                if ($info['is_main']) {
                    echo json_encode(array(
                        'code' => 1,
                        'msg' => '主讲老师不可删除',
                    ));
                    
                    return;
                }

                $result = $link->where(array(
                    'college_id' => $collegeId,
                    'teacher_id' => $id,
                ))
                ->delete();

                if (false !== $result) {
                    echo json_encode(array(
                        'code' => 0,
                        'msg' => '',
                    ));
                    
                    return;
                }
            
            }

        }

        echo json_encode(array(
            'code' => 1,
            'msg' => '操作失败',
        ));
        
        return;
    
    }
}
