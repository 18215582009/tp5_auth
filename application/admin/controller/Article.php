<?php
namespace app\admin\controller;
//use think\Config;
use app\admin\model\Article as ArticleModel;
use think\cache\driver\Redis;

use think\Image;
use think\Request;

class Article extends Base{

	public function model(){
		if(request()->isPost() && input('post.')){
			$article_list = \think\Db::name('article_list')->field('id,name,cat_id,add_time,is_show,is_top,is_recomond,brower,order')->where('cat_id',input('param.id'))->select();
			return json($article_list);
		}else{
			$list = \think\Db::name('article_cat')->field('id,name,pid')->select();
			$article_list = \think\Db::name('article_list')->field('id,name,cat_id,add_time,is_show,is_top,is_recomond,brower,order')->select();
			$ArticleModel = new ArticleModel;
			$cat = $ArticleModel->list_to_tree($list);
			$this->assign('cat',$cat);
			$this->assign('article_list',$article_list);
			return view("model");
		}

	}

	public function list(){

		return view('list');
	}

	public function edit(){


		print_r(input('get.'));exit;

		return view("edit");
	}


	public function del(){


	}

	public function add(){
		

		return view("add");
	}



}
