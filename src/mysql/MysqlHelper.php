<?php

namespace adamyxt\helper\mysql;

use Yii;
use yii\db\Exception;

/**
 * 数据库帮助工具封装类
 * @author AdamYU
 * @version 1.0
 */
class MysqlHelper
{
    const UPDATE_REPLACE = 1;
    const UPDATE_PLUS = 2;
    const UPDATE_MINUS = 3;

    /**
     * Mysql批量修改方法封装
     * @param string $table_name
     * @param string $case_field
     * @param int $update_type
     * @param array $data
     * @return boolean
     * @throws Exception
     */
    public static function batchUpdate(string $table_name, string $case_field, array $data): bool
    {
        if (empty($data)) {
            return false;
        }
        $connection = Yii::$app->db;
        $first_data = reset($data);
        $fields = array_keys($first_data);//获取所有需要修改的字段名
        do {
            //组装开始sql语句
            foreach ($fields as $begin_field) {
                if (reset($fields) == $begin_field) {
                    $$begin_field = "UPDATE {{%" . $table_name . "}} SET " . $begin_field . " = CASE " . $case_field . " ";
                } else {
                    $$begin_field = "" . $begin_field . " = CASE " . $case_field . " ";
                }
            }
            $ids = '';
            $total_sql_count = '';
            foreach ($data as $id => $ordinal) {
                foreach ($ordinal as $field => $value) {
                    $$field .= self::sprintfSql($field, $id, $value);
                    $total_sql_count .= $$field;
                }
                $ids .= $id . ',';
                unset($data[$id]);
                if (count($data) == 0 || strlen($total_sql_count) > 800000) {
                    $ids = substr($ids, 0, -1);
                    $sql = '';
                    foreach ($fields as $end_field) {
                        if (end($fields) == $end_field) {
                            $sql .= $$end_field;
                        } else {
                            $sql .= $$end_field . 'END,';
                        }
                    }
                    $sql .= "END WHERE " . $case_field . " IN ($ids)";
                    break;
                }
            }
            $resultData = $connection->createCommand($sql)->execute();
            if ($resultData == 0) {
                throw new Exception('没有修改成功');
            }
        } while (count($data) > 0);

        return true;
    }

    /**
     * 根据字段类型拼接sql语句
     * @param string $field
     * @param string $case_field
     * @param $field_value
     * @return string
     * @throws Exception
     */
    private static function sprintfSql(string $field, string $case_field, $field_value): string
    {
        if (is_array($field_value)) {
            if (isset($field_value['type'])) {
                switch ($field_value['type']) {
                    case 1:
                        return sprintf('WHEN %s THEN %s ', $case_field, $field_value['value']);
                        break;
                    case 2:
                        return sprintf('WHEN %s THEN ' . $field . '+%s ', $case_field, $field_value['value']);
                        break;
                    case 3:
                        return sprintf('WHEN %s THEN ' . $field . '-%s ', $case_field, $field_value['value']);
                        break;
                    default:
                        throw new Exception('批量修改助手中没有此类型');
                }
            } else {
                return sprintf('WHEN %s THEN %s ', $case_field, $field_value['value']);
            }

        } else {
            return sprintf('WHEN %s THEN %s ', $case_field, $field_value);
        }
    }

}