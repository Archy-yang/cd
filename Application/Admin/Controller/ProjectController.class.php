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
            ))
            ->join("inner join inverstor as i on i.id = p.inverstor_id")
            ->where($where)
            ->order('index_sort desc')
            ->select();

        $funding = array(
            '天使轮',
            'A轮',
            'B轮',
            'C轮',
            'D轮',
        );

        $this->assign("funding", $funding);
        $this->assign("list", $list);
        $this->display();
    }
    /**
     * 项目列表
     *
     * @autor archy
     */
    public function projectList()
    {
        $project = M('project');
        
        $count = $project->where(array('is_delete' => 0))->count();

        $page = new Page($count, 25);
        $show = $page->show();

        $list = $project->where(array('is_delete' => 0))
            ->limit($page->firstRow, $page->listRows)
            ->select();

        $funding = array(
            '天使轮',
            'A轮',
            'B轮',
            'C轮',
            'D轮',
        );

        $this->assign("funding", $funding);
        $this->assign("list", $list);
        $this->assign("show", $show);
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
            'msg' => '',
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
            'msg' => '',
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
        $project = D('project');

        $result = $project->where(array('id' => $id))->data(array('is_delete' => 1))->save();

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

    public function signUpList($inverstorId)
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

        $this->display();
    }
}
