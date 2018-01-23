<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use App\Repositories\Eloquent\CategoryRepositoryEloquent as CategoryRepository;
use App\Http\Controllers\Controller;
use Cookie;
use Session;
class CategoryController extends Controller
{
    private $category;


    public function __construct(CategoryRepository $categoryRepository)
    {
        // $this->middleware('CheckPermission:categorys');
        $this->category = $categoryRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function request_post($url = '', $post_data = array()) {
            if (empty($url) || empty($post_data)) {
                return false;
            }

            $o = "";
            foreach ( $post_data as $k => $v )
            {
                $o.= "$k=" . urlencode( $v ). "&" ;
            }
            $post_data = substr($o,0,-1);



            $postUrl = $url;
            $curlPost = $post_data;
            $ch = curl_init();//初始化curl
            curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
            curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
            curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
            curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
            $data = curl_exec($ch);//运行curl
            curl_close($ch);

            return $data;
        }




    function encrypt($input,$key) {
        $size = mcrypt_get_block_size('des', 'ecb');
        $input = $this->pkcs5_pad($input, $size);

        $td = mcrypt_module_open('des', '', 'ecb', '');
        $iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        @mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data);
        return $data;
    }

    function pkcs5_pad ($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }


    public function index()
    {

        //调邮箱的接口
        //  $url = 'http://maillogin.linekong.com/loginauth';
        //  $email = urlencode('wangliangliang');

        //  $password = 'Wangliang2807';
        //  $key ='linekong';
        //  $password =  urlencode($this->encrypt($password,$key));



        //  $sign = urlencode(Md5($email.'linekongline'));

        //  $data = array(
        //     'email' => $email,
        //     'password' =>$password,
        //     'sign'  =>$sign
        //     );

        // $res =  $this->request_post($url,$data);




        $field = ['id','parent_id','title','order','isopen','updated_at','created_at'];

        $cate = $this->category->sortCateSetCache($field);

        $cateList = $this->category->getCateList($cate);


        $cates = $this->category->setPrefix();

        return view('admin.category.index',['list'=>$cateList,'cates' => $cates]);
    }


    /**
     * Store a newly created resource in storage.
     * @param CategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CategoryRequest $request)
    {

        $this->category->createCategory($request->all());
        return redirect('admin/category');
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $cate  = $this->category->find($id)->toArray();
        $cates = $this->category->setPrefix();
        return view('admin.category.edit',compact('cate','cates'));
    }

    /**
     * Update the specified resource in storage.
     * @param CategoryRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(CategoryRequest $request, $id)
    {

        $res = $this->category->update($request->all(),$id);
        if ($res){
            flash('菜单保存成功','success');
        }else{
            flash('菜单保存失败','error');
        }
        return redirect('admin/category');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //删除菜单前判断子类是否存在
        $is_children = $this->category->is_children($id);
        if($is_children){
            flash('先删除该分类下子类','error');
            return redirect('admin/category');
        }else{
        $res = $this->category->delete($id);
        if ($res){
            flash('分类删除成功','success');
        }else{
            flash('分类删除失败','error');
        }
        return redirect('admin/category');
        }
    }
}
