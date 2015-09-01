<?php

namespace Admin\Controller;

use Think\Controller;

/**
 * @author archy
 */
class UploadController extends Controller
{
    public function uploadImg()
    {
        trace($_FILES);   

        $filesData = $_FILES['Filedata'];

        $suff = explode('.', $filesData['name']);

        if (!in_array($suff['1'], array('jpg', 'gif', 'png'))) {
            echo json_encode(array(
                'code' => '1',
                'msg' => '',
                'path' => '',
            ));
            return;
        }

        $path = '/Uploads/Picture/';
        $fileName = md5(time().$suff[0]).".".$suff['1'];
        move_uploaded_file($filesData['tmp_name'], '.'.$path.$fileName);
        
        echo json_encode(array(
            'code' => '0',
            'msg' => '',
            'path' => $path,
            'name' => $fileName,
        ));
        return ;
    }
}
