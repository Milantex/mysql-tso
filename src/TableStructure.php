<?php
    namespace Milantex\TSO;

    use Milantex\DAW\DataBase;

    /**
     * The TableStructure class
     *
     * @author Milantex
     */
    class TableStructure {
        /**
         * The Milantex DataBase Access Wrapper object
         * @var DataBase
         */
        private $daw;

        /**
         * The name of the table
         * @var string
         */
        private $tableName;

        /**
         * The list of table fields
         * @var array
         */
        private $fields = [];

        /**
         * Returns the list of fields.
         * Fields are of type FieldStructure.
         * @return array
         */
        public function getFields() : array {
            return $this->fields;
        }

        /**
         * Returns the list of field names
         * @return array
         */
        public function getFieldNames() : array {
            return array_keys($this->fields);
        }

        /**
         * Returns the field structure object for the specified field
         * @param string $name
         * @return \Milantex\TSO\FieldStructure
         */
        public function getFieldStructure(string $name) {
            return $this->fields[$name] ?? NULL;
        }

        /**
         * The TableStructure class constructor.
         * @param DataBase $daw
         * @param string $tableName
         */
        function __construct(DataBase &$daw, string $tableName) {
            $this->daw = $daw;
            $this->tableName = preg_replace('/[^A-z0-9_]/', '', $tableName);

            $fields = $this->daw->selectMany('SHOW FULL COLUMNS FROM `' . $this->tableName . '`;');
            foreach ($fields as $field) {
                $fieldStructure = new FieldStructure($field);
                $this->fields[$fieldStructure->getName()] = $fieldStructure;
            }
        }
    }
