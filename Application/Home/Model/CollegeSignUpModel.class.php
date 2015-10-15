<?php

namespace Home\Model;

use Think\Model;

/**
 * @author archy
 */
class CollegeSignUpModel extends Model
{
    protected $_validate = array(
        array('name', 'require', '请填写姓名'),
        array('company', 'require', '请填写公司'),
        array('job', 'require', '请填写职位'),
        array('mobile', 'require', '请填写手机'),
        array('mobile', 'number', '手机格式不正确'),
        array('mobile', '11', '手机长度为11位', 1, 'length'),
        array('email', 'require', '请填写邮箱'),
        array('email', 'email', '邮箱格式错误'),
    );

    protected $_auto = array(
        array('create_time', 'getTime', self::MODEL_INSERT, 'callback'),
    );

    public function getTime()
    {
        return date('Y-m-d H:i:s');
    }
}
