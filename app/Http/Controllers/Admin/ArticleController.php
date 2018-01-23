<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ArticleRequest;
use App\Repositories\Eloquent\ArticleRepositoryEloquent as ArticleRepository;
use App\Http\Controllers\Controller;
use App\Weixin\JsApiPay;
// use App\XmlRpc\EToolKit2;
use App\Libs\LineKong\XmlRpc\EToolKit2;
use Response;
use Cookie;
use Cache;
use Session;
class ArticleController extends Controller
{
    private $article;

    public function __construct(ArticleRepository $articleRepository)
    {
        // $this->middleware('CheckPermission:menus');
        $this->article = $articleRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

              $res = $this->article->with('belongsToAdmins')->find(9)->toArray();

              dump($res);


               $res = $this->article->with('belongsToManyTag')->find(1)->toArray();
               dd($res);





               $field = ['id','title','abstract', 'content', 'content_md','article_image', 'article_status', 'display_name', 'comment_count', 'author', 'updated_at'];
                $articles = $this->article->getAll($field);
                return view('admin.article.index',compact('articles'));
    }

    public function Tojson($code=200,$_msg='',$data=''){
            $result = ['code'=>$code, 'message'=>$_msg,'data'=>empty($data) ? '' : $data];
            $result = json_encode($result, JSON_UNESCAPED_UNICODE);
            return $result;
     }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        $topMenus = $this->menu->findWhere(['parent_id'=>0]);

        return view('admin.menu.create',compact('topMenus'));

    }

    /**
     * Store a newly created resource in storage.
     * @param MenuRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(ArticleRequest $request)
    {
   $i = Cookie::get('test1');
                   dd($i);
        dd($request->all());
        // $this->menu->createMenu($request->all());
        // return redirect('admin/menus');
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



        $topMenus = $this->menu->findWhere(['parent_id'=>0]);
        $menu = $this->menu->find($id)->toArray();
        return view('admin.menu.edit',compact('topMenus','menu'));
    }

    /**
     * Update the specified resource in storage.
     * @param MenuRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(ArticleRequest $request, $id)
    {
        // dd($request->all());

        $res = $this->menu->update($request->all(),$id);
        if ($res){
            flash('菜单保存成功','success');
        }else{
            flash('菜单保存失败','error');
        }
        return redirect('admin/menus');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = $this->menu->delete($id);
        if ($res){
            flash('菜单删除成功','success');
        }else{
            flash('菜单删除失败','error');
        }
        return redirect('admin/menus');
    }



}
