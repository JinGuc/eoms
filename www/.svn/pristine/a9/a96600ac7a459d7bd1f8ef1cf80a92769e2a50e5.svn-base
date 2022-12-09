<?php

namespace App\Http\Controllers\Api\v1\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * 通讯录列表
     * @param Request $request
     * @return array
     */
    public function list(Request $request)
    {
        Log::info("获取通讯录列表信息", ["request" => $request]);
        $page = $request->input('page', 1);
        $limit = $request->input('pagesize', 20);

        $query = Contact::query();


        if($request->input('name')) $query->where('name', 'like', "%".$request->input('name')."%");
        if($request->input('phone')) $query->where('phone', $request->input('phone'));
        if($request->input('status')) $query->where('status', $request->input('status'));

        $query->orderBy("updated_at", 'DESC');
        $total = $query->count();


        $ContactList = $query->offset(($page - 1) * $limit)
            ->limit($limit);


        if($ContactList->exists())
        {
            $ContactList = $ContactList->get()
            ->toArray();
            $resList = array(
                'data' => $ContactList,
                'total' => $total

            );
            return ["status" => "success", "des" => "获取成功", "res" => $resList];
        }
        return ["status" => "success", "des" => "无数据", "res" => []];
    }


    /**
     * 通讯录添加或修改
     * @param Request $request
     * @return array|string[]
     */
    public function addOrEdit(Request $request)
    {
        Log::info("添加或修改通讯录信息", ["request" => $request]);
        $act = $request->input('act','');//当前动作，表示想做另外的事
        $id = $request->input('id','');//传了为编辑，未传为新增
        $ContactObj = $id ? Contact::find($id) : new Contact;
        if($id && empty($ContactObj)){
            return ["status" => "fail", "des" => '未知的对象', "res"=>[]];
        }
        if($act == 'changeStatus'){//该动作表示只更新状态字段
            if(!$id){
                return ["status" => "fail", "des" => '无效的id'];
            }
            $ContactObj->status = $request->input('status',Contact::$_status['on']['code']);

        }else{
            $validate = Validator::make($request->all(),[
                "phone"=>[
                    "required",
                    "regex:/^1(3|5|7|6|8|4)[\d]{9}$/",
                ],
                "name"=>"required",
                "email"=>[
                    "email"
                ],
            ],[
                "phone.required"=>"手机不能为空",
                "phone.regex"=>"手机非法",
                "name.required"=>"姓名不能为空",
                "email.email"=>"邮箱非法",
            ]);
            if($validate->fails()){
                return ["status" => "fail", "des" => $validate->errors()->first(), 'res'=>[]];
            }
            $ContactExists = Contact::where(function($query) use ($request){
                    $email = $request->input('email');
                    return $query->where('phone',$request->input('phone'))
                        ->when($email, function($query, $email){
                            return $query->orWhere('email',$email);
                        });
                })
                ->when($id, function($query, $id){
                    return $query->where('id', '!=',$id);
                });
            if($ContactExists->exists())
            {
                return ["status" => "fail", "des" => '已存在相同的手机号或邮箱记录。', 'res'=>[]];
            }
            $ContactObj->name = $request->input('name');
            $ContactObj->phone = $request->input('phone');
            $ContactObj->email = $request->input('email','');
            $ContactObj->status = $request->input('status',Contact::$_status['on']['code']);
        }
        if($ContactObj->save())
        {
            $resList = [
                'data' => [
                    'id' => $ContactObj->id
                ]
            ];
            return ["status" => "success", "des" => '保存成功', "res" => $resList];
        }
        return ["status" => "fail", "des" => '保存失败，存在相同数据', 'res'=>[]];
    }

    /**
     * 通讯录详情
     * @param Request $request
     * @return array
     */
    public function info(Request $request)
    {

        Log::info("通讯录信息详情获取", ["request" => $request]);
        if(!$request->filled(['id'])){
            return ["status" => "fail", "des" => '未知的参数', 'res'=>[]];
        }
        $ContactObj = Contact::find($request->input('id'));
        if($ContactObj){
            $ContactObj = $ContactObj->toArray();
            return ["status" => "success", "des" => '获取成功', 'res'=>["data"=>$ContactObj]];
        }
        return ["status" => "fail", "des" => '获取失败', 'res'=>[]];
    }

    /**
     * 通讯录删除
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        Log::info("通讯录信息删除", ["request" => $request]);
        if(!$request->filled(['id'])){
            return ["status" => "fail", "des" => '未知的参数', 'res'=>[]];
        }
        $ContactObj = Contact::find($request->input('id'));
        if($ContactObj->delete()){
            return ["status" => "success", "des" => '删除成功', 'res'=>[]];
        }
        return ["status" => "fail", "des" => '删除失败', 'res'=>[]];
    }

    /**
     * 启用的通讯录列表
     * @param Request $request
     * @return array
     */
    public function EnableList(Request $request)
    {
        Log::info("获取启用的通讯列表信息", ["request" => $request]);

        $query = Contact::query();

        $query->orderBy("updated_at", 'DESC');
        $query->where('status',1);



        if($query->exists())
        {
            $ContactList = $query->get()
                ->toArray();
            $resList = array(
                'data' => $ContactList

            );
            return ["status" => "success", "des" => "获取成功", "res" => $resList];
        }
        return ["status" => "success", "des" => "无数据", "res" => []];
    }

}
