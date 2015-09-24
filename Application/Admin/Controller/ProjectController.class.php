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
        $project = M('inverstor');

        $where = array(
            'i.is_delete' => 0,
            'p.is_pass' => 1,
            'p.is_index' => 1,
        );
        //$field = array(
        //    'i.project_name',
        //);

        $list = $project->alias('i')
            ->field($field)
            ->join('left join project as p on p.inverstor_id = i.id')
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
        $this->display('projectlist');
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
            $data['funds'] *= 100;

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
}
