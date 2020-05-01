<?php

namespace App\Handlers;

use  Illuminate\Support\Str;

//文件夹来存放本项目的工具类
class ImageUploadHandler
{

    protected $allowed_ext = ["png", "gif", "jpg", "jpeg"];

    public function save($file,$folder,$file_prefix)
    {
        //构建存图片的文件夹 值如：uploads/images/avatars/201709/21/
        $folder_name = "uploads/images/$folder/".date("Y/m/d",time());

        //具体文件夹的存放路径
        $upload_path = public_path().'/'.$folder_name;

        // 获取文件的后缀名，因图片从剪贴板里黏贴时后缀名为空，所以此处确保后缀一直存在
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // 拼接文件名，加前缀是为了增加辨析度，前缀可以是相关数据模型的 前缀是自动生成的标识
        // $filename  ID值如：1_1493521050_7BVc9v9ujP.png
        $filename = $file_prefix . '_' . time() . '_' . Str::random(10) . '.' . $extension;

        if(!in_array($extension,$this->allowed_ext)){
            return false;
        }

        $file->move($upload_path,$filename);

        return [
          'path' => config('app.url')."/$folder_name/$filename"
        ];

    }

}

