<?php

namespace Admin\Model;

use Think\Model;
/**
 * @author archy
 */
class CollegeModel extends Model
{
    protected $_auto = array(
        array('create_time', 'getTime', self::MODEL_INSERT, 'callback'),
    );

    public function getTime()
    {
        return date('Y-m-d H:i:s');
    }
}
