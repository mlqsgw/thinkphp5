<?php
namespace app\demo\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Image;
use think\Session;
use think\File;

use app\demo\model\User as UserModel;
use app\demo\model\VideoProp as VideoPropModel;

class Index extends Controller
{ 
    public function index(){

        return $this->fetch();
    }

    public function user_list(){

        $search_data = $request->get();
        $search_name  = isset($search_data['search_name']) ? $search_data['search_name'] : '';
        $status_time = isset($search_data['st']) ? $search_data['st'] : '';
        $status_time = strtotime($status_time);
        $end_time = isset($search_data['et']) ? $search_data['et'] : '';
        $end_time = strtotime($end_time);

        $m_user = new UserModel;
        $m_family = new FamilyModel;
        $m_distributionLog = new DistributionLogModel;

        $list = $m_family->alias('family')->where('family.id=1')  
            ->join('m_user as b on b.family_id = family.id')  
            ->join('m_distributionLog as c on c.from_user_id = b.id')  
            ->order('b.create_time')  
            ->select();
        print_r($list);exit;

        if($search_name){

            $where['id'] = $search_name;
            $m_video_prop = new VideoPropModel;
            $lists = $m_video_prop->where($where)->order('id desc')->paginate(10);
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










    	$m_user = new UserModel;

    	$lists = $m_user->order('id','desc')->paginate(1);
    	$page = $lists->render();

    	$this->assign('lists', $lists);
    	$this->assign('page', $page);

    	return $this->fetch();
    }


    //导出家族直播管理
    public function payExport(Request $request)
    {	
    	$name =trim(date("Y/m/d",time()));
    	//获取要导出的数据
    	$m_user = new UserModel;
    	$data = $m_user->order('id','desc')->select();
    	
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
        ->setCellValue('A1',"用户数据")
            ->setCellValue('A2',"用户id")
            ->setCellValue('B2', "昵称")
            ->setCellValue('C2', "姓名")
            ->setCellValue('D2', "所属单位ID")

            ->setCellValue('E2', "状态")
            ->setCellValue('F2', "角色ID")
            ->setCellValue('G2', "注册人ID")
            ->setCellValue('H2', "备注")
            ->setCellValue('I2', "是否可用")


            ;
        foreach($data as $k => $v){
        	// $create_time = date("Y-m-d H:i",$v["create_time"]); 
            $num=$k+1;
            $objPHPExcel->setActiveSheetIndex(0)//Excel的第A列，uid是你查出数组的键值，下面以此类推
            ->setCellValue('A'.($num+2),$v['id'])
                ->setCellValue('B'.($num+2), $v['username'])
                ->setCellValue('C'.($num+2), $v['realname'])
                ->setCellValue('D'.($num+2), $v['acc_unit_id'])

                ->setCellValue('E'.($num+2), $v['status'])
                ->setCellValue('F'.($num+2), $v['role_id'])
                ->setCellValue('G'.($num+2), $v['register_id'])
                ->setCellValue('H'.($num+2), $v['memo'])
                ->setCellValue('I'.($num+2), $v['enable'])
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
        header('Content-Disposition: attachment;filename="用户数据表'.$name.'.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    public function upload_image(){
        
        return $this->fetch();
    }

    public function upload_image_to(){
        $file = $this->request->file('file');//file是传文件的名称，这是webloader插件固定写入的。因为webloader插件会写入一个隐藏input，不信你们可以通过浏览器检查页面
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
    }

    public function main(){

    	return $this->fetch();
    }

    public function left(){
    	return $this->fetch();
    }

    public function top(){
    	return $this->fetch();
    }

    public function right(){
    	return $this->fetch();
    }
}
