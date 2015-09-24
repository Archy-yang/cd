<?php

namespace Admin\Model;

use Think\Model;
/**
 * @author archy
 */
class InverstorModel extends Model
{
    protected $_validate = array(
        array('funding', 'number', '已备资金只能填写数字'),
        array('mobile', 'number', '电话格式错误', 0, 11),
    );

    protected $_auto = array(
        array('create_time', 'getTime', self::MODEL_INSERT, 'callback'),
    );

    public function getTime()
    {
        return date('Y-m-d H:i:s');
    }
}
