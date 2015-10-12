<?php

namespace Admin\Model;

use Think\Model;
/**
 * @author archy
 */
class TeacherModel extends Model
{
    protected $_validate = array(
        array('img_name', 'require', '请上传图片', 1),
        array('name', 'require', '请填写姓名', 1),
        array('desc', 'require', '请填写简介', 1),
    );
    protected $_auto = array(
        array('create_time', 'getTime', self::MODEL_INSERT, 'callback'),
    );

    public function getTime()
    {
        return date('Y-m-d H:i:s');
    }
}
