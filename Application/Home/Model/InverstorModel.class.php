<?php

namespace Home\Model;

use Think\Model;
/**
 * @author archy
 */
class InverstorModel extends Model
{
    protected $_validate = array(
        array('name', 'require', '请填写姓名'),
        array('mobile', 'require', '请填写电话', 0, 11),
        array('mobile', 'number', '电话格式错误', 0, 11),
        array('profile', 'require', '请填写个人简介'),
        array('project_name', 'require', '请填写项目名称'),
        array('job', 'require', '请填写职务'),
        array('company_location', 'require', '请填写所在城市'),
        array('img_name', 'require', '请上传照片'),
        array('company_fund', 'require', '请填写预计投资资金'),
        array('tag', 'require', '请填写项目标签'),
        array('goal', 'require', '请填写挂牌方向'),
        array('company_fund', 'require', '请填写预计投资资金'),
        array('inver_goal', 'require', '请填写投资标的'),
        array('project_desc', 'require', '请填写项目简介'),
        array('resource', 'require', '请填写可提供资源列表'),
        array('need', 'require', '请填写需求'),
        array('mode', 'require', '请填写合作模式'),
        array('company_situation', 'require', '请填写公司现状'),
    );

    protected $_auto = array(
        array('create_time', 'getTime', self::MODEL_INSERT, 'callback'),
    );

    public function getTime()
    {
        return date('Y-m-d H:i:s');
    }
}
