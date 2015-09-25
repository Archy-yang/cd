<?php

namespace Home\Controller;

/**
 * @author archy
 */
class InverstorController extends HomeController
{
    public function info($id)
    {
        $info = M('inverstor')->where(array(
            'id' => $id
        ))
        ->find();

        $info['resource'] = explode("\n", $info['resource']);
        $info['need'] = explode("\n", $info['need']);

        $this->assign('info', $info);
        $this->display();
    }

    public function signUp($id = 0)
    {
        $signUp = D('Inverstor_sign_up');

        $data = $signUp->create();

        $flag = false;
        if ($id && $data) {
            $data['inverstor_id'] = $id;
            $flag = $signUp->data($data)->add();
            
        }

        if ($flag) {
            echo json_encode(array(
                'code' => 0,
            ));
        } else {
            echo json_encode(array(
                'code' => 1,
                'msg' => $signUp->getError() ? $signUp->getError() : '申请失败',
            ));
        }
        return ;
    }

    public function saveSignUp()
    {
        $inverstor = D('Inverstor');

        $data = $inverstor->create();

        $flag = false;
        if ($data) {
            $flag = $inverstor->data($data)->add();
        }

        if ($flag) {
            $this->success('申请成功', U('Inverstor/info'));
        } else {
            $this->error('申请失败');
        }
    }
}
