<?php
namespace Mss\Databases;
use Mss\Files\File;
use mysql_xdevapi\Exception;
use PDO;

class DB{
    protected static $instance;
    protected static $connection;
    protected static $table;
    protected static $query;
    protected static $select;
    protected static $join;
    protected static $where;
    protected static $groupBy;
    protected static $having;
    protected static $orderBy;
    protected static $limit;
    protected static $offset;
    protected static $binding = [];
    protected static $where_binding = [];
    protected static $having_binding = [];

    private function __construct ()
    {
    }

    private static function connect(){
        if (!self::$connection){
            $db = File::require_file ('config/database.php');
            extract ($db);
            $dns = 'mysql:dbname='.$db_name.';host='.$db_host.'';
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_PERSISTENT=>true,
                PDO::MYSQL_ATTR_INIT_COMMAND=>'set NAMES '.$charset.' COLLATE '.$collation
            ];
            try {
                self::$connection = new PDO($dns,$db_user,$db_password,$options);
            }catch (\PDOException $exception){
                throw new \Exception($exception->getMessage ());
            }
        }
    }

    private static function instance(){
        self::connect ();
        if (!self::$instance){
            self::$instance = new DB();
        }
        return self::$instance;
    }

    public static function query($query = null){
        self::instance ();
        if (!$query){
            if (!self::$table){
                throw new \Exception('unknown table');
            }
            $query = 'SELECT ';
            $query .= self::$select?:'*';
            $query .= ' FROM '.self::$table ." ";
            $query .= self::$join . " ";
            $query .= self::$where. " ";
            $query .= self::$groupBy. " ";
            $query .= self::$having. " ";
            $query .= self::$orderBy. " ";
            $query .= self::$limit. " ";
            $query .= self::$offset. " ";
        }
        self::$query = $query;
        self::$binding = array_merge (self::$having_binding,self::$where_binding);
        return self::instance ();
    }

    public static function select(...$select){
//        $select = func_get_args();
        $select = implode (', ',$select);
        self::$select = $select;
        return self::instance ();
    }

    public static function join($table,$first,$operator,$second,$type='INNER'){
        self::$join .= " ". $type . " JOIN " . $table . " ON " . $first ." ". $operator ." ". $second . " ";
        return self::instance ();
    }

    public static function rightJoin($table,$first,$operator,$second){
       return self::join ($table,$first,$operator,$second,'RIGHT');
    }

    public static function leftJoin($table,$first,$operator,$second){
        return self::join ($table,$first,$operator,$second,'LEFT');
    }



    public static function table($table){
        self::$table = $table;
        return self::instance ();
    }

    public static function where($column,$operator,$value,$type=null){
        $where = "`$column` $operator ?";
        if (!self::$where){
            $statement = " WHERE $where";
        }else{
            if(!$type){
                $statement = " AND $where";
            }else{
                $statement = " $type $where";
            }
        }
        self::$where .= $statement;
        self::$where_binding[] = htmlspecialchars ($value);
        return self::instance ();
    }

    public static function orWhere($column,$operator,$value){
        return self::where ($column,$operator,$value,'OR');
    }

    public static function sql(){
        return static::$query;
    }

    public static function get(){
       return self::query (self::$query);
    }
}