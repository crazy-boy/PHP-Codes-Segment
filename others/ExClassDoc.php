<?php

/**
 * Class ExClassDoc  自动生成文档
 * 根据反射的分析类，接口，函数和方法的内部结构，方法和函数的参数，以及类的属性和方法，可以自动生成文档。
 */
Class ExClassDoc{

    public static function exClass($class){
        $ref = new ReflectionClass($class);
        $str = "<table>";
        $str .= "<tr><th colspan='4' class='title'>".$ref->getName()."&nbsp;&nbsp;&nbsp;".self::getComment($ref)."</th></tr>";

        $str .= "<tr><th colspan='4'>属性列表</th></tr>";
        $str .= "<tr class='sub-title'><th>Name</th><th>Access</th><th colspan='2'>Comment</th></tr>";
        $attr = $ref->getProperties();
        foreach ($attr as $row) {
            $str .= "<tr><td>".$row->getName()."</td><td>".self::getAccess($row)."</td><td colspan='2'>".self::getComment($row)."</td></tr>";
        }

        $str .= "<tr><th colspan='4'>常量列表</th></tr>";
        $str .= "<tr class='sub-title'><th colspan='2'>Name</th><th colspan='2'>Value</th></tr>";
        $const = $ref->getConstants();
        foreach ($const as $key => $val) {
            $str .= "<tr><td colspan='2'>".$key."</td><td colspan='2'>".$val."</td></tr>";
        }

        $str .= "<tr><th colspan='4'>方法列表</th></tr>";
        $str .= "<tr class='sub-title'><th>Name</th><th>Access</th><th>Params</th><th>Comment</th></tr>";
        $methods = $ref->getMethods();
        foreach ($methods as $row) {
            var_dump($row->getDocComment());
            $str .= "<tr><td>".$row->getName()."</td><td>".self::getAccess($row)."</td><td>".self::getParams($row)."</td><td>".self::getComment($row,"param")."</td></tr>";
        }

        //die;
        $str .= "</table>";


        $str .= <<<goCss
        <style>
           th,td{height: 20px;font-size: 14px;border: 1px solid #CCC;background-color: #fff;padding: 5px;}
           table{border-collapse: collapse;margin: auto;width: 70%;}
           .tltle{background-color: lightgrey;}
           .sub-title th{background-color: lightblue;}
        </style>
goCss;

        echo $str;
    }


    // 获取权限
    private static function getAccess($method){
        if ($method->isPublic()) {
            return 'Public';
        }
        if ($method->isProtected()) {
            return 'Protected';
        }
        if ($method->isPrivate()) {
            return 'Private';
        }
    }

    // 获取注释
    private static function getComment($var,$type="func"): string
    {
        $comment = $var->getDocComment();
        switch ($type){
            case "func":
                preg_match('/\* (.*) */', $comment, $res);
                break;
            case "param":
                preg_match('/\* @param(.*) */', $comment, $res);
                break;
            case "return":

                break;
        }
        preg_match('/\* (.*) */', $comment, $res);
        
        return $res[1] ?? '';
    }

    // 获取方法参数信息
    private static function getParams($method){
        $arr = [];
        $parameters = $method->getParameters();
        foreach ($parameters as $row) {
            $tmp = $row->getName() . '&nbsp;&nbsp;&nbsp;';
            if ($row->isDefaultValueAvailable()) {
                $tmp .= "Default: {$row->getDefaultValue()}";
            }
            $arr[] = $tmp;
        }

        return $arr ? join("<br/>",$arr): '';
    }

}





/**
 * 学生类
 *
 * 描述信息
 */
class Student
{
    const NORMAL = 1;           //正常状态
    const FORBIDDEN = 2;
    /**
     * 用户ID
     * @var 类型
     */
    public $id;
    /**
     * 获取id
     * @return int 返回ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @param $name 名称
     */
    public function setId($id = 1,$name)
    {
        $this->id = $id;
    }
}

$test = ExClassDoc::exClass('Student');
