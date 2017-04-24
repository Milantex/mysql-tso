<?php
    namespace Milantex\TSO;

    use Milantex\DAW\DataBase;

    /**
     * The TableStructureDescriptor class
     *
     * @author Milantex
     */
    class TableStructureDescriptor {
        /**
         * The Milantex DataBase Access Wrapper object
         * @var DataBase
         */
        private $daw;

        /**
         * The list of database tables
         * @var array
         */
        private $tables = [];

        /**
         * Return the list of all tables in the database.
         * @return array
         */
        public function getTables() : array {
            return $this->tables;
        }

        /**
         * Return the list of names of all tables in the database.
         * @return array
         */
        public function getTableNames() : array {
            return array_keys($this->tables);
        }

        /**
         * Returns the TableStructure object for the specified table.
         * @param string $name
         * @return \Milantex\TSO\TableStructure|NULL
         */
        public function getTableStructure(string $name) {
            return $this->tables[$name] ?? NULL;
        }

        /**
         * Table Structure Descriptor object constructor. Takes a DAW
         * object as a single argument. The DAW object specifies the
         * database which the TSO should preform analysis of.
         * @param DataBase $daw
         */
        function __construct(DataBase &$daw) {
            $this->daw = $daw;
        }

        /**
         * Performs database table structure analysis
         */
        function analyse() {
            $tables = $this->daw->selectMany('SHOW tables;');
            foreach ($tables as $table) {
                $tableName = array_values((array) $table)[0];
                $this->tables[$tableName] = new TableStructure($this->daw, $tableName);
            }
        }
    }
