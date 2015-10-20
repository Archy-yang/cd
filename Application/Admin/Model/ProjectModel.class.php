<?php

namespace Admin\Model;

use Think\Model;

/**
 * @author archy
 */
class ProjectModel extends Model
{
    protected $_validate = array(
        array('img_name', 'require', '请上传图片', 1),
        array('recruit', 'require', '请填写人才招募', 1),
        array('main_type', 'require', '请填写主类型', 1),
    );

    protected $_auto = array(
        array('create_time', 'getTime', self::MODEL_INSERT, 'callback'),
    );

    public function getTime()
    {
        return date('Y-m-d H:i:s');
    }
}
