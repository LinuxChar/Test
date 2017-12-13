<?php


	// base64上传处理
	function b64_upload($file = '',$path = '',$name = 0)
	{
	    if(empty($file)) return ['errcode'=>0,'errmsg'=>'无上传文件'];

	    // 上传目录
	    $ymd = date('Ymd');
	    $dir = '.';
	    $path = empty($path) ? '/public/uploads/base64/'.$ymd.'/': $path;
	    
	    // 目录不存在则创建
	    if(!is_dir($dir.$path)){
	        mkdir($dir.$path,0777,true);
	    }

	    // 允许的类型
	    $allows = ['jpg','gif','png','jpeg'];


	    // 图像处理
	    $img = str_replace(array('_','-'), array('/','+'), $file);
	        
	    $b64img = substr($img, 0,100);
	    
	    if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $b64img, $matches)){ 
	        $type = $matches[2];
	        if(!in_array($type, $allows)){
	            return array('errcode'=>0,'errmsg'=>'图片格式不支持');
	        }
	    }
	    $img = base64_decode(str_replace($matches[1], '', $img));
	    
	    // 文件名格式
	    switch($name){
	        case '1':
	            list($msec, $sec) = explode(' ', microtime());
	            $name = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000).mt_rand(100,999) . '_' . md5(uniqid(md5(microtime(true)),true));
	            break;
	        default:
	            $name = md5(uniqid(md5(microtime(true)),true));
	            break;
	    }

	    // 文件名
	    $pic = $path.$name.'.'.$type;
	    
	    $res = file_put_contents($dir.$pic,$img);

	    
	    
	    if(file_exists($dir.$pic) && $res){
	        return [
	            'errcode'=>1,
	            'errmsg'=>'保存成功',
	            'errdata'=>[
	                'pic_url'=>$pic
	            ]
	        ];
	    }else{
	        return ['errcode'=>0,'errmsg'=>'保存失败'];
	    }
	}