<?php
    use Milantex\DAW\DataBase;
    use Milantex\TSO\TableStructure;

    class TableStructureTest extends PHPUnit_Framework_TestCase {
        private $postTableStructure;
        
        public function setUp() {
            $daw = new DataBase('localhost', 'bayou', 'root', '');
            $this->postTableStructure = new TableStructure($daw, 'post');
        }

        public function testGetFields() {
            $fields = $this->postTableStructure->getFields();

            $this->assertTrue(count($fields) == 7, 'There should be 7 fields in the post table.');
        }

        public function testGetFieldNames() {
            $mustHaveThese = ['post_id', 'created_at', 'user_id', 'title', 'link', 'content', 'visible'];

            $names = $this->postTableStructure->getFieldNames();

            foreach ($mustHaveThese as $name) {
                $this->assertTrue(in_array($name, $names));
            }

            foreach ($names as $name) {
                $this->assertTrue(in_array($name, $mustHaveThese));
            }
        }

        public function testFieldExists() {
            $this->assertTrue($this->postTableStructure->fieldExists('user_id'));
            $this->assertFalse($this->postTableStructure->fieldExists('some_other_field'));
        }

        public function testGetFieldStructure() {
            $this->assertInstanceOf('\\Milantex\\TSO\\FieldStructure', $this->postTableStructure->getFieldStructure('post_id'));
            $this->assertNull($this->postTableStructure->getFieldStructure('this_field_does_not_exist'));
        }
    }
