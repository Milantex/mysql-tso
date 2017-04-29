<?php
    use Milantex\DAW\DataBase;
    use Milantex\TSO\TableStructureDescriptor;

    class TableStructureDescriptorTest extends PHPUnit_Framework_TestCase {
        private $tsd;

        public function setUp() {
            $daw = new DataBase('localhost', 'bayou', 'root', '');
            $this->tsd = new TableStructureDescriptor($daw);
            $this->tsd->analyse();
        }

        public function testGetTables() {
            $tables = $this->tsd->getTables();

            $this->assertTrue(is_array($tables));
            $this->assertEquals(2, count($tables));
            $this->assertArrayHasKey('user', $tables);
            $this->assertArrayHasKey('post', $tables);
        }

        public function testGetTableNames() {
            $mustHaveThese = ['post', 'user'];

            $names = $this->tsd->getTableNames();

            $this->assertTrue(is_array($names));

            foreach ($mustHaveThese as $name) {
                $this->assertTrue(in_array($name, $names));
            }

            foreach ($names as $name) {
                $this->assertTrue(in_array($name, $mustHaveThese));
            }
        }

        public function testTableExists() {
            $this->assertTrue($this->tsd->tableExists('post'));
            $this->assertFalse($this->tsd->tableExists('some_imaginarry_table'));
        }

        public function testGetTableStructure() {
            $this->assertNull($this->tsd->getTableStructure('some_imaginarry_table'));

            $userTable = $this->tsd->getTableStructure('user');

            $this->assertInstanceOf('\\Milantex\\TSO\\TableStructure', $userTable);
        }
    }
