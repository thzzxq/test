<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\CategoryRepository as CategoryRepositoryInterface;
use App\Models\Category;

/**
 * Class CategoryRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class CategoryRepositoryEloquent extends BaseRepository implements CategoryRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Category::class;
    }

     public function sortCate($cate,$pid =0)
        {
            $array = [];
            if (!empty($cate)){
                foreach ($cate as $key => $v){
                    if ($v['parent_id']==$pid){
                        $array[$key] = $v;
                        $array[$key]['child'] = self::sortCate($cate,$v['id']);
                    }
                }
            }
            return $array;
        }

        public function sortCateSetCache($columns = ['*'])
        {
            $cate = $this->orderBy('order','asc')->all($columns)->toArray();

            if ($cate){
                $cateList = $this->sortCate($cate);

            foreach ($cateList as $key => $v){
                if ($v['child']){
                    $sort = array_column($v['child'],'order');
                    array_multisort($sort,SORT_ASC,$v['child']);
                }
            }
            return $cateList;
            }
            return '';
        }


        public function getList()
        {
            return $this->sortCateSetCache();
        }


        public function getData()
        {
            $list = $this::all()->toArray();
            return $list;
        }


        public function is_children($id){
            $res = Category::where('parent_id','=', $id)->first();
            return $res;
        }

        /**
         * @param $data
         * @param int $pid
         * @return array
         * 排序树
         */
        public function getTree($data,$pid=0)
        {
            $tree = [];
            foreach ($data as $item){
                if ($item['parent_id'] == $pid){
                    $tree[] = $item;
                    $tree = array_merge($tree,$this->getTree($data,$item['id']));
                }
            }
            return $tree;
        }

        /**
         * @param string $pre
         * @return array
         * 分类前缀
         */
        public function setPrefix($pre = "|——")
        {
            $getData = $this->getData();
            $data = $this->getTree($getData);
            $tree = [];
            $num = 1;
            $prefix = [0 => 0];
            while ($value = current($data)){
                $key = key($data);
                if ($key > 0){
                    if ($data[$key - 1]['parent_id'] != $value['parent_id']){
                        $num++;
                    }
                }
                if (array_key_exists($value['parent_id'],$prefix)){
                    $num = $prefix[$value['parent_id']];
                }
                $value['title'] = str_repeat($pre,$num).$value['title'];
                $prefix[$value['parent_id']] = $num;
                $tree[] = $value;
                next($data);
            }
            return $tree;
        }


        /**
         * 创建分类
         * @param  array  $attributes 传入的数组
         * @return [type]             返回成功值
         */
         public function createCategory(array $attributes)
        {

            $res = $this->create($attributes);
            if ($res){
                flash('菜单新增成功','success');
            }else{
                flash('菜单新增失败','error');
            }
            return $res;
        }




        public function getCateList($cate)
        {

            if ($cate){
                $item = '';
                foreach ($cate as $v){
                    if($v['isopen'] == 1){

                        $v['isopen']= '<button type="button" class="btn btn-xs ink-reaction btn-raised btn-info" id="button-open" data-menu_id="'.$v['id'].'"><i>开启</i></button>';
                    }elseif($v['isopen'] == 2){

                        $v['isopen']= '<button type="button" class="btn btn-xs ink-reaction btn-raised btn-danger" id="button-open" data-menu_id="'.$v['id'].'"><i>关闭</i></button>';
                    }
                    $item .= $this->getComments($v['id'],$v['title'],$v['child'],$v['order'],$v['isopen']);
                }
                return $item;
            }
            return '没有分类';
        }


        public function getComments($id,$title,$child,$order,$isopen)
        {
            if ($child){
                return $this->getHandleList($id,$title,$child,$order,$isopen);
            }
            return ' <li data-order="'.$order.'"class="dd-item " data-id="'.$id.'"><div class="dd-handle"><span class="pull-right">

                '.$isopen.'
             <a href ="'.route('category.edit',$id).'">
             <button type="button" class="btn btn-xs ink-reaction btn-raised  btn-success "><i class="fa fa-pencil"> 编辑</i></button>
             </a>
             <a href ="#">
             <button type="button" class="btn btn-xs ink-reaction btn-raised  btn-danger destroy " data-id="'.$id.'"><i class="fa fa-trash"> 删除</i></button>
             </a>
            </span>'.$title.'</div></li>';
        }

        public function getHandleList($id,$title,$child,$order,$isopen)
        {
            $handle = '<li data-order="'.$order.'" class="dd-item " data-id="'.$id.'"><div class="dd-handle"><span class="pull-right">

              '.$isopen.'
             <a href ="'.route('category.edit',$id).'">
             <button type="button" class="btn btn-xs ink-reaction btn-raised btn-success"><i class="fa fa-pencil"> 编辑</i></button>
             </a>
             <a href ="#">
             <button type="button" class="btn btn-xs ink-reaction btn-raised  btn-danger destroy " data-id="'.$id.'"><i class="fa fa-trash"> 删除</i></button>
             </a>
            </span>'.$title.'</div><ol class="dd-list">';
            foreach ($child as $v){

                 if($v['isopen'] == 1){
                        $v['isopen']= '<button type="button" class="btn btn-xs ink-reaction btn-raised btn-info" id="button-open" data-menu_id="'.$v['id'].'"><i>开启</i></button>';
                    }elseif($v['isopen'] == 2){
                        $v['isopen']= '<button type="button" class="btn btn-xs ink-reaction btn-raised btn-danger" id="button-open" data-menu_id="'.$v['id'].'"><i>关闭</i></button>';
                    }
                $handle .= $this->getComments($v['id'],$v['title'],$v['child'],$v['order'],$v['isopen']);
            }
            $handle .= '</ol></li>';
            return $handle;
        }





}
