<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once(__DIR__ . '/../src/run-command.php');


final class ImportInvoiceTest extends TestCase
{
    public function testParseVerifyInvoiceJson(): void
        {
            setTestDb();
            $aliceId = intval(runCommand([ 'adminParty' => true ], ['register', 'alice', 'alice123'])[0]);
            setUser('alice', 'alice123');
            $fixture = __DIR__ . "/fixtures/verifyInvoice-JSON.json";
            $result = runCommand(getContext(), ["import-invoice", "verifyInvoice-JSON", $fixture,  "2022-03-31 12:00:00" ]);

            $this->assertEquals([
                [
                    'id' => 1,
                    'name' => 'Alex Malikov'
                ]
            ], getAllComponents());
            $this->assertEquals([
                [
                    'id' => 1,
                    'type_' => 'invoice',
                    'fromcomponent' => 1,
                    'tocomponent' => 1,
                    'timestamp_' => '1970-01-01 00:00:00',
                    'amount' => '0'            ]
            ], getAllMovements());
            $this->assertEquals([
                [
                    'id' => 1,
                    'movementid' => 1,
                    'userid' => 1,
                    'sourcedocumentformat' => null,
                    'sourcedocumentfilename' => null,
                    'timestamp_' => '2022-03-31 12:00:00',
                                ]
            ], getAllStatements());
        }

        public function testParseHerokuInvoiceJson(): void
        {
            setTestDb();
            $aliceId = intval(runCommand([ 'adminParty' => true ], ['register', 'alice', 'alice123'])[0]);
            setUser('alice', 'alice123');
            $fixture = __DIR__ . "/fixtures/timeHerokuInvoice-JSON.json";
            $result = runCommand(getContext(), ["import-invoice", "timeHerokuInvoice-JSON", $fixture,  "2022-03-31 12:00:00" ]);

            $this->assertEquals([
                [
                    'id' => 1,
                    'name' => 'alice'
                ],
                [
                    'id' => 2,
                    'name' => '56ae3c14-906b-4858-a8ff-a6347b9bb183'
                ],
                [
                    'id' => 3,
                    'name' => '9aa53ffa-ce4f-487d-ba74-2fffb3c98e04'
                ],
                [
                    'id' => 4,
                    'name' => 'c1f7f8b3-b063-4001-aebd-7a7600ebf206'
                ]
            ], getAllComponents());
            $this->assertEquals([
                [
                    'id' => 1,
                    'type_' => 'invoice',
                    'fromcomponent' => 1,
                    'tocomponent' => 2,
                    'timestamp_' => '1970-01-01 00:33:42',
                    'amount' => '0'            
                ],
                [
                        'id' => 2,
                        'type_' => 'invoice',
                        'fromcomponent' => 1,
                        'tocomponent' => 3,
                        'timestamp_' => '1970-01-01 00:33:42',
                        'amount' => '0'           
                ],
                [
                        'id' => 3,
                        'type_' => 'invoice',
                        'fromcomponent' => 1,
                        'tocomponent' => 4,
                        'timestamp_' => '1970-01-01 00:33:42',
                        'amount' => '0'           
                ]
            ], getAllMovements());
            $this->assertEquals([
                [
                    'id' => 1,
                    'movementid' => 1,
                    'userid' => 1,
                    'sourcedocumentformat' => null,
                    'sourcedocumentfilename' => null,
                    'timestamp_' => '2022-03-31 12:00:00',
                ],
                [
                    'id' => 2,
                    'movementid' => 2,
                    'userid' => 1,
                    'sourcedocumentformat' => null,
                    'sourcedocumentfilename' => null,
                    'timestamp_' => '2022-03-31 12:00:00',
                ],
                [
                    'id' => 3,
                    'movementid' => 3,
                    'userid' => 1,
                    'sourcedocumentformat' => null,
                    'sourcedocumentfilename' => null,
                    'timestamp_' => '2022-03-31 12:00:00',
                ]
            ], getAllStatements());
        }
}