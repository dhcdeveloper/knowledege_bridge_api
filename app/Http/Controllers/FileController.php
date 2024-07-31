<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends BaseApiController
{
    public function upload(Request $request)
    {
        $i = 0;
        //循环请求中的所有文件
        while (isset($_FILES['files'.$i])) {
            $uploadFile = $_FILES['files'.$i];
            // 检查是否有错误发生
            if ($uploadFile['error'] == UPLOAD_ERR_OK) {
                $tmpName = $uploadFile['tmp_name'];
                logger()->error($tmpName);
                //上传路径：~/public/uploads/
                $targetPath = "uploads/";
                $targetFile = $targetPath . basename($uploadFile['name']);
                logger()->error($targetFile);
                // 检查文件是否已上传
                if (move_uploaded_file($tmpName, $targetFile)) {
                    logger()->error(__('message.file.upload.success_save').$targetFile);
                } else {
                    logger()->error(__('message.file.upload.wrong_move'));
                    return $this->errorResponse(__('message.file.upload.wrong_move'));
                }
            } else {
                logger()->error(__('message.file.upload.wrong_upload').$uploadFile['error']);
                return $this->errorResponse(__('message.file.upload.wrong_upload').$uploadFile['error']);
            }
            
            $i++;
        }
        return $this->successResponse(['message' => __('message.file.upload.success')]);
    }
}