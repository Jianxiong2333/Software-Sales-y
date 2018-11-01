<?php
/**
 * Created by PhpStorm.
 * User: jianxiong2333
 * Date: 2018-08-30
 * Time: 21:58
 */

/*数据库连接类*/
class pay_db_link
{
    /*数据库连接*/
    public function DB_Link()
    {
        try {
            $dsn = 'mysql:host=123.123.123.123;dbname=software_sales-y';
            $name = 'software_sales-y';
            $pwd = 'software_sales-y';
            return new PDO($dsn, $name, $pwd);
        } catch (PDOException $e) {
            echo '当前数据环境异常，请联系管理员解决。<br>';
            exit();//退出
            //die ("Error!: " . $e->getMessage() . "<br/>");//错误异常
        }
    }
}

/*返回数据行数类*/
class pay_db_r extends pay_db_link
{
    /**
     * 参数：表、字段、数据
     * @param $table string 表名
     * @param $field string 字段名
     * @param $data string 查询数据
     * @return int 返回影响条数
     */
    public function db_r($table, $field, $data)
    {
        try {
            $pdo = $this ->DB_Link();//调用连接数据库
            $pdo -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);//异常警告设置
            $sql = "SELECT * FROM $table WHERE $field = ?";
            $result = $pdo -> prepare($sql);//准备查询语句
            $result -> bindParam(1, $data);//置入数据
            $result -> execute();//执行sql
            return $result -> rowCount();//返回结果集条数
        } catch (PDOException $e) {
            echo '当前读取环境异常，请联系管理员解决。<br>';
            exit();//退出
            //die ("Error!: " . $e->getMessage() . "<br/>");//错误异常
        }
    }
}

/*返回数据库全部行数类*/
class pay_db__r extends pay_db_link
{
    /**
     * 参数：表、字段、数据
     * @param $table string 表名
     * @return int 返回影响条数
     */
    public function db_r($table)
    {
        try {
            $pdo = $this ->DB_Link();//调用连接数据库
            $pdo -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);//异常警告设置
            $sql = "SELECT * FROM $table";
            $result = $pdo -> prepare($sql);//准备查询语句
            $result -> execute();//执行sql
            return $result -> rowCount();//返回结果集条数
        } catch (PDOException $e) {
            echo '当前读取环境异常，请联系管理员解决。<br>';
            exit();//退出
            //die ("Error!: " . $e->getMessage() . "<br/>");//错误异常
        }
    }
}

/*返回数据结果集类*/
class pay_db_r_all extends pay_db_link
{
    /**
     * 参数：表、字段、数据
     * @param $table string 表名
     * @param $field string 字段名
     * @param $data string 查询数据
     * @return array 返回结果集数组
     */
    public function db_r($table, $field, $data)
    {
        try {
            $pdo = $this ->DB_Link();//调用连接数据库
            $pdo -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);//异常警告设置
            $sql = "SELECT * FROM $table WHERE $field = ?";
            $result = $pdo -> prepare($sql);//准备查询语句
            $result -> bindParam(1, $data);//置入数据
            $result -> execute();//执行sql
            return $result -> fetch(PDO::FETCH_ASSOC);//返回关联数组结果集
        } catch (PDOException $e) {
            echo '当前读取数据环境异常，请联系管理员解决。<br>';
            exit();//退出
            //die ("Error!: " . $e->getMessage() . "<br/>");//错误异常
        }
    }
}

/*写数据库类*/
class pay_db_w extends pay_db_link
{
    /**使用PDO规范传入，传参照：“sql语句（带占位符格式‘？’）、写入参数数量（如：2）、数组形式的参数（如：&$arr）”
     * @param $sql string sql语句
     * @param $parm_num int 传参数量
     * @param $parm_arr array 数组形式参数
     * @return int 返回影响条数
     */
    public function db_w($sql, $parm_num, &$parm_arr)//sql语句（占位符格式）、写入参数数量、数组形式的参数
    {
        try {
            $pdo = $this ->DB_Link();//调用连接数据库
            $pdo -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);//异常警告设置
            $result = $pdo -> prepare($sql);//准备查询语句
            while($parm_num)
            {
                // echo $parm_arr[$parm_num];
                $result -> bindParam($parm_num, $parm_arr[$parm_num]);//置入数据 循环次数为传递位数为数组下标，排除0
                $parm_num--;//自减必须写在这里,保证数组下标正确。
            }
            $result -> execute();//执行sql
            return $result -> rowCount();//返回结果集条数
        } catch (PDOException $e) {
            echo '当前写入环境异常，请联系管理员解决。<br>';
            exit();//退出
            //die ("Error!: " . $e->getMessage() . "<br/>");//错误异常
        }
    }
}

/*更新数据库类*/
class pay_up_db extends pay_db_link
{
    /**
     * 参数：表、字段、数据
     * @param $table string 表名
     * @param $field string 字段名
     * @param $after string 替换后
     * @param $condition string 条件
     * @param $data string 数据
     * @return int 返回结果集条数
     */
    public function db_r($table, $field, $after, $condition, $data)
    {
        try {
            $pdo = $this ->DB_Link();//调用连接数据库
            $pdo -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);//异常警告设置
            $sql = "update $table set $field=$after where $condition=?";
            $result = $pdo -> prepare($sql);//准备查询语句
            $result -> bindParam(1, $data);//置入数据
            $result -> execute();//执行sql
            return $result -> rowCount();//返回结果集条数
        } catch (PDOException $e) {
            echo '当前读取数据环境异常，请联系管理员解决。<br>';
            exit();//退出
            //die ("Error!: " . $e->getMessage() . "<br/>");//错误异常
        }
    }
}