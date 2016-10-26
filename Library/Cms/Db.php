<?php

class Cms_Db extends Zend_Db_Table_Abstract
{

        public function replace($data) {
            // get the columns for the table
            $tableInfo = $this->info();
            $tableColumns = $tableInfo['cols'];

            // columns submitted for insert
            $dataColumns = array_keys($data);

            // intersection of table and insert cols
            $valueColumns = array_intersect($tableColumns, $dataColumns);
            sort($valueColumns);

            // generate SQL statement
            $cols = '';
            $vals = '';
            foreach($valueColumns as $col) {
                    $cols .= $this->getAdapter()->quoteIdentifier($col) . ',';
                    $vals .=	(get_class($data[$col]) == 'Zend_Db_Expr')
                                            ? $data[$col]->__toString()
                                            : $this->getAdapter()->quoteInto('?', $data[$col]);
                    $vals .= ',';
            }
            $cols = rtrim($cols, ',');
            $vals = rtrim($vals, ',');
            $sql = 'REPLACE INTO ' . $this->_name . ' (' . $cols . ') VALUES (' . $vals . ');';

            return $this->_db->query($sql);

    }
}

?>
