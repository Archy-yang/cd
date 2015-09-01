<?php

namespace Home\Controller;

/**
 * @author archy
 */
class InverstorController extends HomeController
{
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

        trace($info);

        $this->assign('img', $img);
        $this->assign('info', $info);
        $this->display();
    }

    public function signUp()
    {
        $inverstor = D("Inverstor");

        $data = $inverstor->create();

        if ($data) {
        
        }
    }
}
