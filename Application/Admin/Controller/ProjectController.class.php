<?php
namespace Admin\Controller;

use Think\Page;
/**
 * @author archy
 */
class ProjectController extends AdminController
{
    /**
     * 首页项目列表
     *
     * @author yangqi
     */
    public function indexList()
    {
        $project = M('project');

        $where = array(
            'i.is_delete' => 0,
            'p.is_pass' => 1,
            'p.is_index' => 1,
        );

        $list = $project->alias('p')
            ->field(array(
                'i.*',
                'p.is_index',
                'p.is_pass',
                'p.id' => 'proid',
            ))
            ->join("inner join inverstor as i on i.id = p.inverstor_id")
            ->where($where)
            ->order('index_sort desc')
            ->select();

        $this->assign("list", $list);
        $this->display();
    }
    /**
     * 项目列表
     *
     * @autor archy
     */
    public function projectList($type = 0)
    {
        $inverstor = M('inverstor');
        
        $where = array(
            'i.is_delete' => 0,
        );

        switch ($type) {
            case 1:
                $where['is_pass'] = 1;

                break;
            case 2:
                $where['is_pass'] = array('exp', 'is NULL or is_pass < 1');

                break;
            default:
                break;
        }

        $count = $inverstor->alias('i')
            ->join("left join project as p on i.id = p.inverstor_id")
            ->where($where)
            ->count();

        $page = new Page($count, 30);
        $show = $page->show();


        $list = $inverstor->alias('i')
            ->field(array(
                'i.*',
                'p.is_index',
                'p.is_pass',
                'p.id' => 'proid',
            ))
            ->join("left join project as p on i.id = p.inverstor_id")
            ->where($where)
            ->limit($page->firstRow, $page->listRows)
            ->select();

        $this->assign("list", $list);
        $this->assign("show", $show);
        $this->assign("type", $type);

        $this->display();
    }

    /**
     * 添加项目
     *
     * @author archy
     */
    public function addProject()
    {
        $this->display();
    }

    /**
     * 创建/修改项目的列表显示
     *
     * @authro archy
     */
    public function createProject()
    {
        $id = $_POST['id'];
        trace($_POST);

        if ($id > 0) {
            $this->updateProject();
        } else {
            $this->saveProject();
        
        }
    }

    /**
     * 保存项目
     *
     * @authro archy
     */
    public function saveProject()
    {
        $project = D("Project");

        $data = $project->create();

        if ($data) {
            $data['funds'] *= 100;

            $result = $project->data($data)->add();

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
            'msg' => $project->getError() ? $project->getError() : '操作失败 ',
        ));

        return ;
    }

    /**
     * 编辑项目
     *
     * @author archy
     */
    public function editProject($id)
    {
        $project = M('project');

        $info = $project->where(array("id" => intval($id)))->find();

        $this->assign('info', $info);

        $this->display();
    }

    /**
     * 更新项目
     *
     * @author archy
     */
    public function updateProject()
    {
        $project = D("Project");

        $data = $project->create();

        if ($data) {
            $id = $data['id'];

            if (!isset($data['is_funding'])) {
                $data['is_funding'] = 0;
            }
            $data['funds'] *= 100;

            if ($id > 0) {
                unset($data['id']);

                $result = $project->where(array('id' => intval($id)))->data($data)->save();

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
            'msg' => $project->getError() ? $project->getError() : '操作失败 ',
        ));

        return ;
    }

    /**
     * 删除项目
     *
     * @auhtor archy
     */
    public function deleteProject($id)
    {
        $inverstor = D('inverstor');

        $result = $inverstor->where(array('id' => $id))->data(array('is_delete' => 1))->save();

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

    public function signUpList($inverstorId, $l = 1)
    {
        $model = M("inverstor_sign_up");
        $inverstor = M('inverstor')->field(array("project_name"))
            ->where(array('id' => $inverstorId))
            ->find();

        $count = $model->where(array(
            'is_delete' => 0,
            'inverstor_id' => $inverstorId,
        ))->count();
        $page = new Page($count, 25);
        $show = $page->show();

        $list = $model->where(array(
            'is_delete' => 0,
            'inverstor_id' => $inverstorId,
        ))
        ->limit($page->firstRow, $page->listRows)
        ->select();

        $this->assign("project", $inverstor['project_name']);
        $this->assign('show', $show);
        $this->assign('list', $list);
        $this->assign('l', $l);

        $this->display();
    }

    /**
     * 设为首页
     */
    public function setIndex($id)
    {
        if ($id > 0) {
            $result = M('project')->where(array('id' => $id))
                ->data(array('is_index' => 1))
                ->save();

            if (false !== $result) {
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

        return ;
    }

    /**
     * 取消首页
     */
    public function unIndex($id)
    {
        if ($id > 0) {
            $result = M('project')->where(array('id' => $id))
                ->data(array('is_index' => 0))
                ->save();

            if (false !== $result) {
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

        return ;
    }

    /**
     * 通过审核
     */
    public function pass($id)
    {
        if ($id > 0) {
            $result = M('project')->where(array('id' => $id))
                ->data(array('is_pass' => 1))
                ->save();

            if (false !== $result) {
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

        return ;
    }

    /**
     * 取消审核
     */
    public function unpass($id)
    {
        if ($id > 0) {
            $result = M('project')->where(array('id' => $id))
                ->data(array('is_pass' => 0))
                ->save();

            if (false !== $result) {
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

        return ;
    }
}
