<?php
namespace app\admin\controller;
use app\admin\model\Users as UsersModel;
use Phpoffice\Phpexcell\Classses\PHPExcel;

class Users extends Base{



	public function index(){


		echo 123;
	}
    /**
     * 用户列表
     * @author xiewen
     * @DateTime 2018-03-23T13:20:01+0800
     * @return   [type]                   [description]
     */
	public function list(){
		$page = 3;
		$list = \think\Db::name('users')->paginate($page)->each(function($item,$key){
		return $item;
		});
		$page = $list->render();
		$data = $list->all();
		$this->assign('data',$data);
		$this->assign('page',$page);
		return $this->fetch();
	}
    /**
     * 添加用户
     * @author xiewen
     * @DateTime 2018-03-23T13:20:01+0800
     * @return   [type]                   [description]
     */
	public function add(){
		if(request()->isPost() && input('post.')){
			$UsersModel = new UsersModel;
			if($UsersModel->validate(true)->save(input('post.'))){
				return $this->success('添加成功','list');
			}else{
				return $this->error($UsersModel->getError());
			}
		}else{
			return view("add");
		}
	}
    /**
     * 编辑用户
     * @author xiewen
     * @DateTime 2018-03-23T13:20:01+0800
     * @Emaill 1205669226@qq.com
     * @return   [type]                   [description]
     */
	public function edit(){
		if(request()->isPost() && input('post.')){
			$UsersModel = new UsersModel;
			if($UsersModel->save(input('post.'),['id'=>input('param.id')])){
				return $this->success('更新成功','list');
			}else{
				return $this->error($UsersModel->getError());
			}
		}else{
			$id = input('?param.id') ? input('param.id') : '';
			if(!$id){
				return $this->error('参数错误');
			}
			$data = \think\Db::name('users')->where('id',$id)->field('username,password')->find();
			$this->assign('data',$data);
			return view("edit");
		}
	}
    /**
     * 删除用户
     * @author xiewen
     * @DateTime 2018-03-23T13:20:01+0800
     * @Emaill 1205669226@qq.com
     * @return   [type]                   [description]
     */
	public function del(){
		$id = input('?param.id') ? intval(input('param.id')) : '';
		if(!$id){
			return $this->error('参数错误');
		}
		if(\think\Db::name('users')->where('id',$id)->delete()){
			return $this->success('删除成功');
		}else{
			return $this->error('删除失败');
		}
	}

    /**
     * 导出excell用户表
     * @author xiewen
     * @DateTime 2018-03-23T13:20:01+0800
     * @Emaill 1205669226@qq.com
     * @return   [type]                   [description]
     */
	public function export_excell(){
		$type = input('?param.type') ? input('param.type') : '';
		if($type == "excell"){
			$objPHPExcel = new \PHPExcel();
			$filename = 'users.xls';
			for ($i=1; $i < 3; $i++) {
				if ($i>1) {
					$objPHPExcel->createSheet();
				}
				$objPHPExcel->setActiveSheetIndex($i-1);//把当前创建的sheet设置为活动sheet
				$objSheet = $objPHPExcel->getActiveSheet();//获得当前活动Sheet
				$objSheet->getStyle('A1')->getFont()->getColor()->setARGB('FFFF0000');
				$objSheet->getStyle('B1')->getFont()->getColor()->setARGB('FFFF0000');
				$objSheet->getStyle('C1')->getFont()->getColor()->setARGB('FFFF0000');
				$objSheet->getStyle('D1')->getFont()->getColor()->setARGB('FFFF0000');
				$objSheet->getStyle('E1')->getFont()->getColor()->setARGB('FFFF0000');
				$objSheet->getStyle('F1')->getFont()->getColor()->setARGB('FFFF0000');
				$objSheet->getStyle('G1')->getFont()->getColor()->setARGB('FFFF0000');
				$objSheet->getStyle('H1')->getFont()->getColor()->setARGB('FFFF0000');
				$objSheet->getStyle('I1')->getFont()->getColor()->setARGB('FFFF0000');
				$objSheet->getStyle('A1')->getAlignment()->setWrapText(true);
				$objSheet->getStyle('B1')->getAlignment()->setWrapText(true);
				$objSheet->getStyle('C1')->getAlignment()->setWrapText(true);
				$objSheet->getStyle('D1')->getAlignment()->setWrapText(true);
				$objSheet->getStyle('E1')->getAlignment()->setWrapText(true);
				$objSheet->getStyle('F1')->getAlignment()->setWrapText(true);
				$objSheet->getStyle('G1')->getAlignment()->setWrapText(true);
				$objSheet->getStyle('H1')->getAlignment()->setWrapText(true);
				$objSheet->getStyle('I1')->getAlignment()->setWrapText(true);
				$objSheet->getStyle('A1')->getFill()->getStartColor()->setARGB('F5DEB3');
				$objSheet->setTitle("users".$i);
				if($i==1){
					$data = \think\Db::name('users')->field('id,username,ip,source,register_time,login_time,score,grade,pid')->select();
				}else{
					$data = \think\Db::name('users')->field('id,username,ip,source,register_time,login_time,score,grade,pid')->select();
				}
				$objSheet->setCellValue('A1','ID')->setCellValue('B1','会员名称')->setCellValue('C1','ip')->setCellValue('D1','来源')->setCellValue('E1','注册时间')->setCellValue('F1','最后登录时间')->setCellValue('G1','积分')->setCellValue('H1','等级')->setCellValue('I1','推荐人ID');
				$j = 2;
				foreach ($data as $key => $value) {
					$objSheet->setCellValue('A'.$j,$value['id'])->setCellValue('B'.$j,$value['username'])->setCellValue('C'.$j,$value['ip'])->setCellValue('D'.$j,$value['source'])->setCellValue('E'.$j,$value['register_time'])->setCellValue('F'.$j,$value['login_time'])->setCellValue('G'.$j,$value['score'])->setCellValue('H'.$j,$value['grade'])->setCellValue('I'.$j,$value['pid']);
					$j++;
				}
			}
			$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			header('Content-Type: application/vnd.ms-excel');//告诉浏览器将要输出excel03文件
			header('Content-Disposition: attachment;filename="'.$filename.'"');//告诉浏览器将输出文件的名称(文件下载)
			header('Cache-Control: max-age=0');//禁止缓存
			$objWriter->save("php://output");

			}
	}
    /**
     * 导入excell用户表
     * @author xiewen
     * @DateTime 2018-03-23T13:20:01+0800
     * @Emaill 1205669226@qq.com
     * @return   [type]                   [description]
     */
	public function import_excell(){

		print_r();exit;




	}



}
