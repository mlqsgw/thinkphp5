<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Image;
use think\Session;

use app\index\model\FamilyUser as FamilyUserModel;
use app\index\model\Family as FamilyModel;
use app\index\model\PlayStatisticsl as PlayStatisticslModel;
use app\index\model\PlayDetail as PlayDetailModel;
use app\index\model\DayDetail as DayDetailModel;
use app\index\model\GiftDetail as GiftDetailModel;
use app\index\model\Contribution as ContributionModel;


class Index extends Controller
{	
	//主页面
	public function main(){
		return $this->fetch();
	}

	//头部
	public function top(){
		return $this->fetch();
	}
	//左侧
	public function left(){
		return $this->fetch();
	}

	//右侧
	public function right(){
		$m_user = db('user');
		$lists  = $m_user->where('is_del','=0')->order('id','desc')->paginate(10);
		$page = $lists->render();
		
		$this->assign('lists', $lists);
		$this->assign('page', $page);
		return $this->fetch();
	}

	//管理员添加页面
	public function user_add(){
		return $this->fetch();
	}

	//执行管理员添加操作
	public function user_add_do(){
		$data = $_POST;

		if (!$data['username']) {
			$result = array(
				"status" => false,
				"message" => "用户名不能为空"
			);
		} else if (!$data['password']){
			$result = array(
				"status" => false,
				"message" => "密码不能为空"
			);
		} else if ($data['password'] != $data['rpassword']) {
			$result = array(
				"status" => false,
				"message" => "密码和确认密码不一致"
			);
		} else {
			$create_time = time();
			$save_data = ['username' => $data["username"], 'password' => md5($data["password"]), 'create_time' => $create_time];

			$m_user = db('user');
			$request = $m_user->insert($save_data);

			if ($request) {
				$result = array(
					"status" => true
				);
			} else {
				$result = array(
					"status" => false,
					"message" => "添加失败"
				);
			}
		}

		return $result;
	}

	//用户修改页面
	public function user_exit(){
		$id = $_GET['id'];

		$m_user = db('user');
		$userData = $m_user->where('id', '=', $id)->find();

		$this->assign('userData', $userData);
		$this->assign('id', $id);
		return $this->fetch();
	}

	//执行用户修改操作
	public function user_exit_do (){
		$data = $_POST;

		if (!$data['username']) {
			$result = array(
				"status" => false,
				"message" => "用户名不能为空"
			);
		} else if (!$data['password']){
			$result = array(
				"status" => false,
				"message" => "密码不能为空"
			);
		} else if ($data['password'] != $data['rpassword']) {
			$result = array(
				"status" => false,
				"message" => "密码和确认密码不一致"
			);
		} else {
			$create_time = time();
			$save_data = ['username' => $data["username"], 'password' => md5($data["password"]),'id' => $data["id"]];

			$m_user = db('user');
			$request = $m_user->update($save_data);

			if ($request) {
				$result = array(
					"status" => true
				);
			} else {
				$result = array(
					"status" => false,
					"message" => "修改失败"
				);
			}
		}

		return $result;

	}

	//用户删除操作
	public function user_del(){
		$data = $_POST;
		$m_user = db('user');
		$request = $m_user->update(['is_del' => 1, 'id' => $data['id']]);

		if ($request) {
			$result = array(
				"status" => true
			);
		} else {
			$result = array(
				"status" => false,
				"message" => "用户删除失败"
			);
		}

		return $result;
	}

	//文章列表页
	public function article(){

		$m_article = db('article');
		$lists  = $m_article->where('is_del','=0')->order('id','desc')->paginate(10);
		$page = $lists->render();
		
		$this->assign('lists', $lists);
		$this->assign('page', $page);
		return $this->fetch();
	}

	//文章添加页面
	public function article_add(){

		return $this->fetch();
	}

	//执行文章添加操作
	public function article_add_do(){
		$data = $_POST;

		if (!$data['title']) {
			$result = array(
				"status" => false,
				"message" => "文章标题不能为空"
			);
		} else if (!$data['intro']){
			$result = array(
				"status" => false,
				"message" => "文章简介不能为空"
			);
		} else if (!$data['content']) {
			$result = array(
				"status" => false,
				"message" => "文章内容不能为空"
			);
		} else {
			$create_time = time();
			$save_data = ['title' => $data["title"], 'intro' => $data["intro"], 'content' => $data["content"], 'create_time' => $create_time];

			$m_article = db('article');
			$request = $m_article->insert($save_data);

			if ($request) {
				$result = array(
					"status" => true
				);
			} else {
				$result = array(
					"status" => false,
					"message" => "添加失败"
				);
			}
		}

		return $result;

	}

	//文章修改页面
	public function article_exit(){
		$id = $_GET['id'];

		$m_article = db('article');
		$articleData = $m_article->where('id', '=', $id)->find();

		$this->assign('articleData', $articleData);
		$this->assign('id', $id);
		return $this->fetch();
	}

	//执行文章修改操作
	public function article_exit_do(){
		$data = $_POST;
		
		if (!$data['title']) {
			$result = array(
				"status" => false,
				"message" => "文章标题不能为空"
			);
		} else if (!$data['intro']){
			$result = array(
				"status" => false,
				"message" => "文章简介不能为空"
			);
		} else if (!$data['content']) {
			$result = array(
				"status" => false,
				"message" => "文章内容不能为空"
			);
		} else {
			
			$save_data = ['title' => $data["title"], 'intro' => $data["intro"], 'content' => $data["content"], 'id' => $data['id']];

			$m_article = db('article');
			$request = $m_article->update($save_data);

			if ($request) {
				$result = array(
					"status" => true
				);
			} else {
				$result = array(
					"status" => false,
					"message" => "修改失败"
				);
			}
		}

		return $result;
	}


	//文章删除操作
	public function article_del(){
		$data = $_POST;
		$m_article = db('article');
		$request = $m_article->update(['is_del' => 1, 'id' => $data['id']]);

		if ($request) {
			$result = array(
				"status" => true
			);
		} else {
			$result = array(
				"status" => false,
				"message" => "文章删除失败"
			);
		}

		return $result;
	}


	//文件上传页面
	public function upload_add(){
		return $this->fetch();
	}

	//文件上传
	public function upload(Request $request){

		//获取表单上传文件
		$file = $request->file('file');

		if(empty($file)){
			$this->error("请选择上传文件");
		}

		//移动框架应用根目录/public/uploads/目录下
		$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
		
		if($info){
			$this->success('文件上传成功: ' . $info->getRealPath());
		} else {
			//上传文件获取错误信息
			$this->error($file->getError());
		}
	}

	//图片上传页面
	public function picture_add(){
		return $this->fetch();
	}

	// 图片上传处理
    public function picture(Request $request)
    {
        // 获取表单上传文件
        $file = $request->file('image');
        if (true !== $this->validate(['image' => $file], ['image' => 'require|image'])) {
            $this->error('请选择图像文件');
        } else {
            // 读取图片
            $image = Image::open($file);
            // 图片处理
            switch ($request->param('type')) {
                case 1: // 图片裁剪
                    $image->crop(300, 300);
                    break;
                case 2: // 缩略图
                    $image->thumb(150, 150, Image::THUMB_CENTER);
                    break;
                case 3: // 垂直翻转
                    $image->flip();
                    break;
                case 4: // 水平翻转
                    $image->flip(Image::FLIP_Y);
                    break;
                case 5: // 图片旋转
                    $image->rotate();
                    break;
                case 6: // 图片水印
                    $image->water('./logo.png', Image::WATER_NORTHWEST, 50);
                    break;
                case 7: // 文字水印
                    $image->text('ThinkPHP', VENDOR_PATH . 'topthink/think-captcha/assets/ttfs/1.ttf', 20, '#ffffff');
                    break;
            }
            // 保存图片（以当前时间戳）
            $saveName = $request->time() . '.png';
            $image->save(ROOT_PATH . 'public/uploads/' . $saveName);
            $this->success('图片处理完毕...', '/uploads/' . $saveName, 1);
        }
    }


    /*
    	**************************************家族管理*******************************************
     */

    //家族管理列表
    public function family_list(Request $request){


    	$m_family = db('family');
    	//获取用户类型  1 管理员 0 家族长
    	$user_status = 1;
    	if($user_status){
    		$where = ['is_del' => 0];
    		$lists  = $m_family->where($where)->order('id','desc')->paginate(10);
    	} else {

    		//获取家族长id
    		$family_id = 11;
    		$where = [
    			'is_del' => 0,
    			'family_id' => $family_id
    		];
    		$lists  = $m_family->where($where)->order('id','desc')->paginate(10);
    	}

    	
		$page = $lists->render();
		
		$this->assign('user_status', $user_status);
		$this->assign('lists', $lists);
		$this->assign('page', $page);
		return $this->fetch();
    }

    //家族添加页面
    public function family_add(){

    	return $this->fetch();
    }



	//文件上传
	public function upload_image(Request $request){

		//获取表单上传文件
		$file = $request->file('file');
		
		//移动框架应用根目录/public/uploads/目录下
		$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
		$image_url = $info->getRealPath();
		return $image_url;
	}

    //执行家族添加
    public function family_add_do(Request $request){
    	$form_data = $request->post();
    	// $family_image = $this->upload_image($request);


    	if (!$form_data['family_id']){
    		$result = [
					"status" => false,
					"message" => "家族ID不能为空！"
			];
    	} elseif (!$form_data['family_big_id']){
    		$result = [
					"status" => false,
					"message" => "家族长ID不能为空！"
			];
    	} elseif (!$form_data["family_name"]){
    		$result = [
					"status" => false,
					"message" => "家族名称不能为空！"
			];
    	} elseif (!$form_data["coefficient"]){
    		$result = [
					"status" => false,
					"message" => "提成系数不能为空！"
			];
    	} elseif (!$form_data["family_declaration"]) {
    		$result = [
					"status" => false,
					"message" => "家族宣言不能为空！"
			];
    	}  else {
    		
    		$create_time = time();
			$save_data = ['family_id' => $form_data["family_id"],'family_big_id' => $form_data["family_big_id"], 'family_name' => $form_data["family_name"], 'coefficient' => $form_data["coefficient"], 'family_declaration' => $form_data["family_declaration"], 'remark' => $form_data['remark'], 'create_time' => $create_time];

			$m_family = db('family');
			$request = $m_family->insert($save_data);

			if ($request) {
				$result = [
					"status" => true
				];
			} else {
				$result = [
					"status" => false,
					"message" => "添加失败"
				];
			}
    	}
    	
    	return json_encode($result);
    	
    }

    //查看家族资料
    public function family_data(Request $request){
    	$form_data = $request->param();
    	
    	// $m_family = new FamilyModel;
    	$m_family = db('family');
    	$family_data = $m_family->where('id', '=', $form_data['id'])->find();

    	$this->assign('family_data', $family_data);
    	return $this->fetch();
    }	

    //家族修改页面
    public function family_exit(Request $request){
    	$form_data = $request->param();
    	//用户状态
    	$user_status = 1;

		$m_family = db('family');
		$familyData = $m_family->where('id', '=', $form_data['id'])->find();

		$this->assign('user_status', $user_status);
		$this->assign('familyData', $familyData);
		$this->assign('id', $form_data['id']);
		return $this->fetch();
    }

    //执行家族修改
    public function family_exit_do(Request $request){
    	$form_data = $request->post();
    	
    	$family_image = $this->upload_image($request);
    	if (!$form_data['family_big_id']){
    		$result = array(
    			"status" => false,
    			"message" => "家族长ID不能为空！"
    		);
    	} elseif (!$form_data["family_name"]){
    		$result = array(
    			"status" => false,
    			"message" => "家族名称不能为空！"
    		);
    	} elseif (!$form_data["coefficient"]){
    		$result = array(
    			"status" => false,
    			"message" => "提成系数不能为空！"
    		);
    	} elseif (!$form_data["family_declaration"]) {
    		$result = array(
    			"status" => false,
    			"message" => "家族宣言不能为空！"
    		);
    	} elseif ($family_image == "") {
    		$result = array(
    			"status" => false,
    			"message" => "家族头像不能为空！"
    		);
    	} 
    	else {
    		$create_time = time();
			$save_data = ['family_big_id' => $form_data["family_big_id"], 'family_name' => $form_data["family_name"], 'coefficient' => $form_data["coefficient"], 'family_declaration' => $form_data["family_declaration"], 'remark' => $form_data['remark'],'family_image' => $family_image,'create_time' => $create_time, 'id' => $form_data["id"]];

			$m_family = db('family');
			$request = $m_family->update($save_data);

			if ($request) {
				$result = array(
					"status" => true
				);
			} else {
				$result = array(
					"status" => false,
					"message" => "修改失败"
				);
			}
    	}

    	if($result["status"]){
    		$this->success('修改成功');
    	} else {
    		$this->error($result["message"]);
    	}
    }

    //家族删除操作
    public function family_del(Request $request){
    	$family_id_data = $request->param();
    	$family_id_data = explode(',', $family_id_data['isc']);
    	
		$m_family = new FamilyModel;
		
		foreach ($family_id_data as $key => $value) {
			
			$request = $m_family->update(['id' => $value, 'is_del' => 1]);
		}
		
		if ($request) {
			$result = array(
				"status" => true
			);
		} else {
			$result = array(
				"status" => false,
				"message" => "家族删除失败"
			);
		}

		return $result;
    }

    //家族直播管理列表
    public function live_streaming_list(Request $request){
    	$search_data = $request->get();
   		$search_name  = isset($search_data['search_name']) ? $search_data['search_name'] : '';
		$m_family_user = new FamilyUserModel;

   		if ($search_name) {
   			$where = [
   				'is_del' => 0,
   				'id' => $search_name
   			];

   			Session::set('live_streaming_where',$where);

   			$lists  = $m_family_user->with('family')->where($where)->order('id','desc')->paginate(10);
   			$list = $lists->toArray();
   			
   			if(!isset($list['data'][0])){
   				$where = [
	   				'is_del' => 0,
	   				'user_name' => $search_name
	   			];

	   			Session::set('live_streaming_where',$where);

   				$lists  = $m_family_user->with('family')->where($where)->order('id','desc')->paginate(10);
   				$list = $lists->toArray();
   			}

   			if(!isset($list['data'][0])){
   				$where = [
	   				'is_del' => 0,
	   				'user_phone' => $search_name
	   			];

	   			Session::set('live_streaming_where',$where);

   				$lists  = $m_family_user->with('family')->where($where)->order('id','desc')->paginate(10);
   				$list = $lists->toArray();
   			}

   			if(!isset($list['data'][0])){
   				$where = [
	   				'is_del' => 0,
	   				'family_id' => $search_name
	   			];

	   			Session::set('live_streaming_where',$where);

   				$lists  = $m_family_user->with('family')->where($where)->order('id','desc')->paginate(10);
   			}


   		} else {
   			$where = [
   				'is_del' => 0
   			];
   			$lists  = $m_family_user->with('family')->where($where)->order('id','desc')->paginate(10);
   		}
    	
		$page = $lists->render();

		// $lists_array = $lists->toArray();
    	// print_r($lists_array);exit;

		$this->assign('lists', $lists);
		$this->assign('page', $page);
		return $this->fetch();




  //   	$m_family_user = db('family_user');
		// $lists  = $m_family_user->where('is_del','=0')->order('id','desc')->paginate(10);

		// $page = $lists->render();
		// $this->assign('lists', $lists);
		// $this->assign('page', $page);
		// return $this->fetch();
    }
    



	
	//导出家族直播管理
    public function payExport(Request $request)
    {	
    	$name =trim(date("Y/m/d",time()));
    	//获取要导出的数据
    	$m_family_user = new FamilyUserModel;
    	$where = session('live_streaming_where');
    	
		$data = $m_family_user->with('family')->where($where)->order('id','desc')->select();

		// $user_id = $request->session('live_streaming_where.user_id');
  //   	$play_detail_id = $request->session('gift_detail_where.play_detail_id');

        vendor('PHPExcel.PHPExcel');
        error_reporting(E_ALL);
        date_default_timezone_set('Europe/London');
        $a=count($data);
        $objPHPExcel = new \PHPExcel();
        /*以下是一些设置 ，什么作者  标题啊之类的*/
        $objPHPExcel->getProperties()->setCreator("firefly")
            ->setLastModifiedBy("firefly")
            ->setTitle("数据EXCEL导出")
            ->setSubject("数据EXCEL导出")
            ->setDescription("数据查看")
            ->setKeywords("excel")
            ->setCategory("result file");
        /*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改*/
        $objPHPExcel->setActiveSheetIndex(0)//Excel的第A列，uid是你查出数组的键值，下面以此类推
        ->setCellValue('A1',"家族直播管理表")
            ->setCellValue('A2',"用户id")
            ->setCellValue('B2', "昵称")
            ->setCellValue('C2', "手机号")
            ->setCellValue('D2', "家族")

            ->setCellValue('E2', "家族粉丝总数")
            ->setCellValue('F2', "家族总魔力值")
            ->setCellValue('G2', "家族总魔力值")
            ->setCellValue('H2', "可结算魔力值")
            ->setCellValue('I2', "当前直播人数")


            ;
        foreach($data as $k => $v){
        	// $create_time = date("Y-m-d H:i",$v["create_time"]); 
            $num=$k+1;
            $objPHPExcel->setActiveSheetIndex(0)//Excel的第A列，uid是你查出数组的键值，下面以此类推
            ->setCellValue('A'.($num+2),$v['id'])
                ->setCellValue('B'.($num+2), $v['user_name'])
                ->setCellValue('C'.($num+2), $v['user_phone'])
                ->setCellValue('D'.($num+2), $v['family']['family_name'])

                ->setCellValue('E'.($num+2), $v['family']['fans_all_num'])
                ->setCellValue('F'.($num+2), $v['family']['magic_all_num'])
                ->setCellValue('G'.($num+2), $v['family']['charm_now_num'])
                ->setCellValue('H'.($num+2), $v['family']['valid_magic_num'])
                ->setCellValue('I'.($num+2), $v['family']['user_num'])
                ;
//设置格式
            /*     $objPHPExcel->getActiveSheet()->getStyle('A'.($num+1))->getNumberFormat()
                     ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_DMYSLASH);*/

        }
//设置单元格宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);

//合并单元格
        $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
//设置字体样式
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
//设置居中
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A3:E3'.($a+1))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->setTitle('User');
        $objPHPExcel->setActiveSheetIndex(0);

        header('Content-Type: applicationnd.ms-excel');
        header('Content-Disposition: attachment;filename="家族直播管理表'.$name.'.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }



    //导出本场打赏明细
    public function play_detail_out(Request $request)
    {	
    	$name =trim(date("Y/m/d",time()));
    	//获取要导出的数据
    	
    	$user_id = $request->session('gift_detail_where.user_id');
    	$play_detail_id = $request->session('gift_detail_where.play_detail_id');


		$m_gift_detail = new GiftDetailModel;
    	$where = [
    		'user_id' => $user_id,
    		'play_detail_id' => $play_detail_id
    	];

    	$data  = $m_gift_detail->where($where)->order('id','desc')->select();

        vendor('PHPExcel.PHPExcel');
        error_reporting(E_ALL);
        date_default_timezone_set('Europe/London');
        $a=count($data);
        $objPHPExcel = new \PHPExcel();
        /*以下是一些设置 ，什么作者  标题啊之类的*/
        $objPHPExcel->getProperties()->setCreator("firefly")
            ->setLastModifiedBy("firefly")
            ->setTitle("数据EXCEL导出")
            ->setSubject("数据EXCEL导出")
            ->setDescription("数据查看")
            ->setKeywords("excel")
            ->setCategory("result file");
        /*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改*/
        $objPHPExcel->setActiveSheetIndex(0)//Excel的第A列，uid是你查出数组的键值，下面以此类推
        ->setCellValue('A1',"本场打赏明细")
            ->setCellValue('A2',"赠送人id")
            ->setCellValue('B2', "赠送人昵称")
            ->setCellValue('C2', "主播ID")
            ->setCellValue('D2', "主播昵称")

            ->setCellValue('E2', "主播家族")
            ->setCellValue('F2', "礼物名称")
            ->setCellValue('G2', "售价")
            ->setCellValue('H2', "售出数量")
            ->setCellValue('I2', "获得魔力值")
            ->setCellValue('J2', "获得贡献值")
            ->setCellValue('K2', "创建时间")
            ;

        foreach($data as $k => $v){
        	
            $num=$k+1;
            $objPHPExcel->setActiveSheetIndex(0)//Excel的第A列，uid是你查出数组的键值，下面以此类推
            ->setCellValue('A'.($num+2),$v['award_user_id'])
                ->setCellValue('B'.($num+2), $v['award_user_name'])
                ->setCellValue('C'.($num+2), $v['user_id'])
                ->setCellValue('D'.($num+2), $v['user_name'])

                ->setCellValue('E'.($num+2), $v['user_family'])
                ->setCellValue('F'.($num+2), $v['gift_name'])
                ->setCellValue('G'.($num+2), $v['price'])
                ->setCellValue('H'.($num+2), $v['sales_quantity'])
                ->setCellValue('I'.($num+2), $v['magic_num'])
                ->setCellValue('J'.($num+2), $v['contribution_num'])
                ->setCellValue('K'.($num+2), $v["create_time"])

                ;
//设置格式
            /*     $objPHPExcel->getActiveSheet()->getStyle('A'.($num+1))->getNumberFormat()
                     ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_DMYSLASH);*/

        }
//设置单元格宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);

//合并单元格
        $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
//设置字体样式
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
//设置居中
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A3:E3'.($a+1))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->setTitle('User');
        $objPHPExcel->setActiveSheetIndex(0);

        header('Content-Type: applicationnd.ms-excel');
        header('Content-Disposition: attachment;filename="家族直播管理表'.$name.'.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }


    //导出本场贡献榜
    public function contribution_out(Request $request)
    {	
    	$name =trim(date("Y/m/d",time()));
    	//获取要导出的数据
    	
    	$user_id = $request->session('contribution_where.user_id');
    	$play_detail_id = $request->session('contribution_where.play_detail_id');


		$m_contribution = new ContributionModel;
    	$where = [
    		'user_id' => $user_id,
    		'play_detail_id' => $play_detail_id
    	];

    	$data  = $m_contribution->where($where)->order('id','desc')->select();

        vendor('PHPExcel.PHPExcel');
        error_reporting(E_ALL);
        date_default_timezone_set('Europe/London');
        $a=count($data);
        $objPHPExcel = new \PHPExcel();
        /*以下是一些设置 ，什么作者  标题啊之类的*/
        $objPHPExcel->getProperties()->setCreator("firefly")
            ->setLastModifiedBy("firefly")
            ->setTitle("数据EXCEL导出")
            ->setSubject("数据EXCEL导出")
            ->setDescription("数据查看")
            ->setKeywords("excel")
            ->setCategory("result file");
        /*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改*/
        $objPHPExcel->setActiveSheetIndex(0)//Excel的第A列，uid是你查出数组的键值，下面以此类推
        ->setCellValue('A1',"本场贡献榜")
            ->setCellValue('A2',"赠送人id")
            ->setCellValue('B2', "赠送人昵称")
            ->setCellValue('C2', "手机号")
            ->setCellValue('D2', "消耗金币数")

            ->setCellValue('E2', "获得贡献值")
            ->setCellValue('F2', "主播ID")
            ->setCellValue('G2', "主播昵称")
            ->setCellValue('H2', "主播家族")
            ->setCellValue('I2', "获得魔力值")
            ;

        foreach($data as $k => $v){
        	
            $num=$k+1;
            $objPHPExcel->setActiveSheetIndex(0)//Excel的第A列，uid是你查出数组的键值，下面以此类推
            ->setCellValue('A'.($num+2),$v['award_user_id'])
                ->setCellValue('B'.($num+2), $v['award_user_name'])
                ->setCellValue('C'.($num+2), $v['award_user_phone'])
                ->setCellValue('D'.($num+2), $v['consume_gold_num'])

                ->setCellValue('E'.($num+2), $v['contribution_num'])
                ->setCellValue('F'.($num+2), $v['user_id'])
                ->setCellValue('G'.($num+2), $v['user_name'])
                ->setCellValue('H'.($num+2), $v['user_family'])
                ->setCellValue('I'.($num+2), $v['magic_num'])
                ;
//设置格式
            /*     $objPHPExcel->getActiveSheet()->getStyle('A'.($num+1))->getNumberFormat()
                     ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_DMYSLASH);*/

        }
//设置单元格宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);

//合并单元格
        $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
//设置字体样式
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
//设置居中
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A3:E3'.($a+1))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->setTitle('User');
        $objPHPExcel->setActiveSheetIndex(0);

        header('Content-Type: applicationnd.ms-excel');
        header('Content-Disposition: attachment;filename="家族直播管理表'.$name.'.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }


   	//家族成员列表
   	public function family_user_list(Request $request){
   		$search_data = $request->get();
   		$search_name  = isset($search_data['search_name']) ? $search_data['search_name'] : '';
   		$status_time = isset($search_data['st']) ? $search_data['st'] : '';
   		$status_time = strtotime($status_time);
   		$end_time = isset($search_data['et']) ? $search_data['et'] : '';
   		$end_time = strtotime($end_time);
   		

   		if($search_name){
   			// if (!$status_time && $end_time) {
   			// 	$where = [
   			// 		'create_time' => ['<',$end_time]
   			// 	];
   			// } elseif ($status_time && !$end_time) {
   			// 	$where = [
   			// 		'create_time' => ['>',$status_time]
   			// 	];
   			// } elseif($status_time && $end_time){
   			// 	$where['create_time'] = array('between',array($status_time,$end_time));
   			// }
   			// print_r($where);exit;

   			$where['id'] = $search_name;
   			$where['is_del'] = 0; 
   			$lists = Db::name('family_user')->where($where)->order('id desc')->paginate(10);
   			$list = $lists->toArray();
   			
   			if(!isset($list['data'][0])){
   				$where_user_name['user_name'] = $search_name;
   				$where_user_name['is_del'] = 0; 
   				
   				$lists = Db::name('family_user')->where($where_user_name)->order('id desc')->paginate(10);
   				$list = $lists->toArray();
   			}
   			if(!isset($list['data'][0])){
   				$where_user_phone['user_phone'] = $search_name;
   				$where_user_phone['is_del'] = 0; 
   				
   				$lists = Db::name('family_user')->where($where_user_phone)->order('id desc')->paginate(10);
   			}
   		} elseif(!$search_name && $status_time && $end_time){
   			$where_time_centre['is_del'] = 0;
   			$lists = Db::name('family_user')->where($where_time_centre)->where('create_time',['>',$status_time],['<',$end_time])->order('id desc')->paginate(10);
   		} elseif (!$search_name && $status_time && !$end_time) {
   			$where_time_type1["is_del"] = 0;
   			$lists = Db::name('family_user')->where($where_time_type1)->where('create_time','>',$status_time)->order('id desc')->paginate(10);
   		} elseif (!$search_name && !$status_time && $end_time){
   			$where_time_type1["is_del"] = 0;
   			$lists = Db::name('family_user')->where($where_time_type1)->where('create_time','<',$end_time)->order('id desc')->paginate(10);
   		} 

   		else {
   			$m_family_user = db('family_user');
			$lists  = $m_family_user->where('is_del','=0')->order('id','desc')->paginate(10);
   		}
   		
		$page = $lists->render();
		$this->assign('lists', $lists);
		$this->assign('page', $page);
   		return $this->fetch();
   	}

   	//查看家族成员资料
    public function family_user_data(Request $request){
    	$form_data = $request->param();
    	
    	// $m_family = new FamilyModel;
    	$m_family = db('family_user');
    	$family_data = $m_family->where('id', '=', $form_data['id'])->find();

    	$this->assign('family_data', $family_data);
    	return $this->fetch();
    }	


    //本期直播明细
    public function play_detail_list(Request $request){

    	$id = $request->get('id');
    	$periods = $request->get('periods');
    	//本期直播统计
    	$m_play_statisticsl = new PlayStatisticslModel;
    	$where = [
    		'user_id' => $id,
    		'periods' => $periods
    	];

    	$play_detail = $m_play_statisticsl->with('familyUser')->where($where)->find();

    	// $play_detail = $m_play_statisticsl->where($where)->find();


    	$this->assign('play_detail', $play_detail);

    	//本期直播明细
    	$m_play_detail = new PlayDetailModel;
    	$where_detail = [
    		'is_del' => 0,
    		'user_id' => $id,
    		'periods' => $periods
    	];

    	Session::set('play_detail_where',$where_detail);

    	$lists  = $m_play_detail->with('familyUser')->where($where_detail)->order('id','desc')->paginate(10,false,['query' => request()->param()]);
    	$page = $lists->render();
    	$this->assign('lists', $lists);
    	$this->assign('page', $page);

  //   	$m_play_detail = new PlayDetailModel;
  //   	// $family_user_list = $m_family_user::get(2);

  //   	$lists  = $m_play_detail->with('family_user')->where('is_del','=0')->order('id','desc')->paginate(10);
		// $page = $lists->render();

		// // $lists_array = $lists->toArray();
  //   	// print_r($lists_array);exit;

		// $this->assign('lists', $lists);
		// $this->assign('page', $page);
		return $this->fetch();
    }

    //本期每日数据
    public function day_detail_list(Request $request){
    	$id = $request->get('id');
    	$periods = $request->get('periods');
    	//本期直播统计
    	$m_play_statisticsl = new PlayStatisticslModel;
    	$where = [
    		'is_del' => 0,
    		'user_id' => $id,
    		'periods' => $periods
    	];
    	$play_detail = $m_play_statisticsl->where($where)->find();

    	$this->assign('play_detail', $play_detail);

    	//本期直播明细
    	$m_day_detail = new DayDetailModel;
    	$where_detail = [
    		'user_id' => $id,
    		'periods' => $periods
    	];

    	$lists  = $m_day_detail->where($where_detail)->order('id','desc')->paginate(10,false,['query' => request()->param()]);
    	
    	$page = $lists->render();
    	$this->assign('lists', $lists);
    	$this->assign('page', $page);
    	return $this->fetch();
    }

    //本场打赏明细
    public function gift_detail_list(Request $request){
    	$user_id = $request->get('user_id');
    	$play_detail_id = $request->get('id');

    	$m_gift_detail = new GiftDetailModel;
    	$where = [
    		'user_id' => $user_id,
    		'play_detail_id' => $play_detail_id
    	];

    	Session::set('gift_detail_where',$where);

    	$lists  = $m_gift_detail->where($where)->order('id','desc')->paginate(10,false,['query' => request()->param()]);

    	$page = $lists->render();
    	$this->assign('lists', $lists);
    	$this->assign('page', $page);
    	return $this->fetch();
    }

    //本场贡献榜
    public function contribution_list(Request $request){
    	$user_id = $request->get('user_id');
    	$play_detail_id = $request->get('id');

    	$m_contribution = new ContributionModel;
    	$where = [
    		'user_id' => $user_id,
    		'play_detail_id' => $play_detail_id
    	];

    	Session::set('contribution_where',$where);

    	$lists  = $m_contribution->where($where)->order('id','desc')->paginate(10,false,['query' => request()->param()]);

    	$page = $lists->render();
    	$this->assign('lists', $lists);
    	$this->assign('page', $page);
    	return $this->fetch();
    }

    //根据用户id获取用户昵称
    public function user_family_data(Request $request){
    	$post_data = $request->post();
    	$user_id = $post_data['user_id'];
    	
    	$m_family_user  = new FamilyUserModel;
    	$where = [
    		'id' => $user_id
    	];
    	$user_family_data = $m_family_user->with('family')->where($where)->find();
    	// $user_family_data = $user_family_data->toArray();
    	// print_r($user_family_data);exit;
    	
    	return $user_family_data;

    }

	
    public function index(){
        return $this->fetch();
    }

    //登录页
    public function login(){
    	return $this->fetch();
    }

    public function computer(){
    	return $this->fetch();
    }

    public function imgtable(){
    	return $this->fetch();
    }

    public function filelist(){
    	return $this->fetch();
    }

    public function form(){
    	return $this->fetch();
    }

    public function imglist(){
    	return $this->fetch();
    }

    public function tools(){
    	return $this->fetch();
    }

    public function tab(){
    	return $this->fetch();
    }

    public function workbench(){
    	return $this->fetch();
    }

    public function imglist1(){
    	return $this->fetch();
    }

    public function error1(){
    	return $this->fetch();
    }

}
