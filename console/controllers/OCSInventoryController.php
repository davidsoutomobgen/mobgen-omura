<?php

/**
 * @author sfraembs
 *
 * @desc OCS Inventory importer
 *
 */
namespace console\controllers;

use Yii;
use yii\console\Controller;
use Everyman\Neo4j\Client;
use Everyman\Neo4j\Cypher\Query;
//use Mongosoft\yii2-soap-client\Client;
//use Mongosoft\Yii2SoapClient\Client;
use SoapClient;
use SoapFault;


class OcsinventoryController extends Controller
{
	public $userid;
	public $ipval;

	public function options()
	{
		return ['userid','ipval'];
	}
	public function optionAliases()
	{
		return ['id' => 'userid', 'ip' => 'ipval'];
	}

	// Default action, in the yii code it looked like it would be called defaultAction()
	public function actionIndex()
    {
        echo "::actionIndex()\n";
    }

    public function actionBoekenbonImportOnlineShops()
    {

    }

    public function actionTest()
    {
        $in_ip = $this->ipval;   //'192.168.5.122';
        $in_net = '192.168.0.0';
        $in_mask = '255.255.240.0';

        $ipval = ip2long($in_ip);
        $ipsnet = ip2long($in_net);
        $ipmask = ip2long($in_mask);

        printf("ip: %s - net: %s - mask: %s\n", long2ip($ipval), long2ip($ipsnet), long2ip($ipmask));
        printf("and: %s AND %s = %s\n", long2ip($ipval), long2ip($ipmask), long2ip($ipval & $ipmask));
        echo "\n";
    }

	public function actionImportHardware()
	{
        echo "actionTest()\n";

        $alocations = [
            ['location_code' => 'KK', 'name' => 'Unknown', 'country' => 'Unknown', 'city' => 'Unknown', 'subnet' => '0.0.0.0', 'subnet_mask' => '255.255.255.255'],
            ['location_code' => 'AMS', 'name' => 'Amsterdam', 'country' => 'The Netherlands', 'city' => 'Amsterdam', 'subnet' => '192.168.0.0', 'subnet_mask' => '255.255.240.0'],
            ['location_code' => 'COR', 'name' => 'La Coruna', 'country' => 'Spain', 'city' => 'La Coruna', 'subnet' => '192.168.16.0', 'subnet_mask' => '255.255.240.0'],
            ['location_code' => 'MAL', 'name' => 'Malaga', 'country' => 'Spain', 'city' => 'Malaga', 'subnet' => '0.0.0.0', 'subnet_mask' => '255.255.255.255'],
            ['location_code' => 'LON', 'name' => 'London', 'country' => 'United Kingdom', 'city' => 'London', 'subnet' => '0.0.0.0', 'subnet_mask' => '255.255.255.255'],
        ];

        /*
         * Set up the neo4j connection
         */
        $neo4j = new Client();
        $neo4j->getTransport()->setAuth('neo4j','none');

        /*
         * OCS Inventory - SOAP setup
         */
        $soap_proto = 'http';
        $soap_host  = 'ocsinventory.mobgenstage.com';
        $soap_port   = '80';
        $soap_user  = '';
        $soap_pass = '';

        $aksing_for = 'INVENTORY';  // INVENTORY | META

        define('OCS_ALL', 131071);
        define('OCS_HARDWARE', 1);
        define('OCS_BIOS', 2);
        define('OCS_MEMORY_SLOTS', 4);
        define('OCS_SYSTEM_SLOTS', 8);
        define('OCS_REGISTRY', 16);
        define('OCS_SYSTEM_CONTROLLERS', 32);
        define('OCS_MONITORS', 64);
        define('OCS_SYSTEM_PORTS', 128);
        define('OCS_STORAGE_PERIPHERALS', 256);
        define('OCS_LOGICAL_DRIVES', 512);
        define('OCS_INPUT_DEVICES', 1024);
        define('OCS_MODEMS', 2048);
        define('OCS_NETWORK_ADAPTERS', 4096);
        define('OCS_PRINTERS', 8192);
        define('OCS_SOUND_ADAPTERS', 16384);
        define('OCS_VIDEO_ADAPTERS', 32768);
        define('OCS_SOFTWARE', 65536);

//        $ocs_checksum = OCS_ALL & ~(OCS_SOFTWARE | OCS_PRINTERS | OCS_MODEMS);
        $ocs_checksum = OCS_HARDWARE | OCS_BIOS | OCS_NETWORK_ADAPTERS | OCS_MONITORS | OCS_SOUND_ADAPTERS | OCS_VIDEO_ADAPTERS;

        echo "checksum bitmap: $ocs_checksum\n";

        $options = array(
            'location'  => "$soap_proto://$soap_host:$soap_port/ocsinterface",
            'uri'       => "$soap_proto://$soap_host:$soap_port/Apache/Ocsinventory/Interface",
            'login'     => $soap_user,
            'password'  => $soap_pass,
            'trace'     => TRUE,
            'soap_version'  => SOAP_1_1,
        );

        // <CHECKSUM>1</CHECKSUM> 131071
        // <WANTED>3</WANTED> 1
        $request =
<<<STREND
        <REQUEST>
            <ENGINE>FIRST</ENGINE>
            <ASKING_FOR>$aksing_for</ASKING_FOR>
            <CHECKSUM>$ocs_checksum</CHECKSUM>
            <OFFSET>0</OFFSET>
            <WANTED>1</WANTED>
        </REQUEST>
STREND;

        try {
            $client = new SoapClient(NULL, $options);
        } catch (Exception $e) {
            echo "Construct Error: " . $e->getMessage() . "\n";
        }

        try {
            $result = $client->get_computers_V1($request);
//            echo "Request: " . $client->__getLastRequest() . "\n";
//            echo "Headers: " . $client->__getLastRequestHeaders() . "\n";
        } catch (Exception $e) {
            echo "Connection Error: " . $e->getMessage() . "\n";
            echo "Headers:\r\n" . $client->__getLastRequestHeaders() . "\n";
            echo "Request:\r\n" . $client->__getLastRequest() . "\n";
            return 1;
        }

            $maxrows = 5;   // Row 0 contains <COMPUTERS> tag and should be ignored
            $rowcnt = 0;
            foreach ($result as $row) {
                $xmldata = @simplexml_load_string($row);
                if ($xmldata === false) {
                    echo "Failed loading XML for row: $rowcnt\n";
                    foreach(libxml_get_errors() as $error) {
                        echo "\t", $error->message;
                    }
                } else {
                    echo "Row[$rowcnt]:\n";
//                    var_dump($xmldata);

                    if (true) {
                        echo "Create a computer in neo4j DB\n";
                        $ahwtags = array();
                        foreach ($xmldata->ACCOUNTINFO->ENTRY as $tag) {
                            $ahwtags[] = $tag;
                        }
                        $hwtags = implode(',', $ahwtags);
                        $props = array(
                            'computer_name' => (string)$xmldata->HARDWARE->NAME,
                            'hardware_tags' => (string)$hwtags,
                            'bios_asset_tag' => (string)$xmldata->BIOS->ASSETTAG,
                            'bios_date' => (string)$xmldata->BIOS->BDATE,
                            'bios_manufacturer' => (string)$xmldata->BIOS->BMANUFACTURER,
                            'bios_version' => (string)$xmldata->BIOS->BVERSION,
                            'bios_smanufacturer' => (string)$xmldata->BIOS->SMANUFACTURER,
                            'bios_smodel' => (string)$xmldata->BIOS->SMODEL,
                            'bios_ssn' => (string)$xmldata->BIOS->SSN,
                            'bios_type' => (string)$xmldata->BIOS->TYPE,
                            'ip_address' => (string)$xmldata->HARDWARE->IPADDR,
                            'description' => (string)$xmldata->HARDWARE->DESCRIPTION,
                            'os_name' => (string)$xmldata->HARDWARE->OSNAME,
                            'os_version' => (string)$xmldata->HARDWARE->OSVERSION,
                            'os_comments' => (string)$xmldata->HARDWARE->OSCOMMENTS,
                            'memory' => (string)$xmldata->HARDWARE->MEMORY,
                            'cpu_type' => (string)$xmldata->HARDWARE->PROCESSORT,
                            'cpu_count' => (string)$xmldata->HARDWARE->PROCESSORN,
                            'cpu_speed' => (string)$xmldata->HARDWARE->PROCESSORS,
                            'video_name' => (string)$xmldata->VIDEOS->NAME,
                            'video_chipset' => (string)$xmldata->VIDEOS->CHIPSET,
                            'video_memory' => (string)$xmldata->VIDEOS->MEMORY,
                            'video_resolution' => (string)$xmldata->VIDEOS->RESOLUTION,
                            'audio_name' => (string)$xmldata->SOUNDS->NAME,
                            'audio_manufacturer' => (string)$xmldata->SOUNDS->MANUFACTURER,
                            'audio_description' => (string)$xmldata->SOUNDS->DESCRIPTION,
                            'quality_score' => (string)$xmldata->HARDWARE->QUALITY,
                            'ocs_user_agent' => (string)$xmldata->HARDWARE->USERAGENT,
                            'user_domain' => (string)$xmldata->HARDWARE->USERDOMAIN,
                            'user_id' => (string)$xmldata->HARDWARE->USERID,
                            'workgroup' =>  (string)$xmldata->HARDWARE->WORKGROUP,
                            'win_owner' =>  (string)$xmldata->HARDWARE->WINOWNER,
                            'win_product_id' =>   (string)$xmldata->HARDWARE->WINPRODID,
                            'win_product_key' =>   (string)$xmldata->HARDWARE->WINPRODKEY,
                            'win_company' =>   (string)$xmldata->HARDWARE->WINCOMPANY,
                        );
                        var_dump($props);

                        /*
                         * Insert (or merge) the Hardware node
                         */
                        echo "Inserting Hardware node using key: ". (string)$xmldata->BIOS->SSN ."\n";
                        $queryTemplate = "MERGE (hardware:Hardware { bios_ssn: {ssn} }) " .
                            "ON CREATE SET hardware = {props} ".
                            "RETURN hardware";
                        $cypher = new Query($neo4j, $queryTemplate, array('ssn'=> (string)$xmldata->BIOS->SSN, 'props' => $props));
                        $results = $neo4j->executeCypherQuery($cypher);
                        $hardware_node = isset($results[0]['hardware']) ? $results[0]['n'] : null;  //$ncnt = count($results);

                        if (!$hardware_node) {
                            echo "Failed to create/merge Hardware node.\n";
                        } else {
                            echo "Successfully created/merged Hardware node\n";

                            /*
                             * Check if we can find the user by creating a mobgen email from the user name
                             */
                            $user_email = strtolower((string)$xmldata->HARDWARE->USERID) . "@mobgen.com";
                            echo "searching for user with email: $user_email\n";
//                          $queryTemplate = "MATCH (n:User {email: {var_email}) LIMIT 1 RETURN n";
//                          $queryTemplate = "MATCH (n:User {internal_email: '{var_email}'}) RETURN n LIMIT 1";
                            $queryTemplate = "MATCH (n:User) WHERE n.internal_email = {var_email} RETURN n";
                            $cypher = new Query($neo4j, $queryTemplate, array('var_email' => $user_email));
                            $results = $cypher->getResultSet();
                            $ncnt = count($results);

                            if ($ncnt > 1) {
                                echo "Warning! We have multiple matches for User nodes email address: $user_email\n";
                                return 1;
                            } else if ($ncnt == 1) {
                                $user_node = $results[0]['n'];
                                echo "We have a matching User nodes on $user_email: " . $user_node->full_name . "\n";

                                /*
                                 * Now create the relations
                                 */
                                $queryTemplate = "MATCH (user:User { person_code: {userid} }),(hardware:Hardware { bios_ssn: {hardwareid} }) " .
                                    "MERGE (user)-[r:HOLDS]->(hardware)";
                                $cypher = new Query($neo4j, $queryTemplate, array('userid'=> $user_node->person_code, 'hardwareid' => $hardware_node->bios_ssn));
                                $results = $neo4j->executeCypherQuery($cypher);

                            }
                        }


                        /*
                         * Try to match the Location based on IP
                         */
                        $locid = 'KK';
                        if (strlen($hardware_node->ip_address) >= 8) {
                            $ipval = ip2long($hardware_node->ip_address);
                            foreach ($alocations as $loc) {
                                $ipmask = ip2long($loc['subnet_mask']);
                                $ipsub = ip2long($loc['subnet']) & $ipmask;

                                $ipval_sub = $ipval & $ipmask;
                                if ($ipval_sub == $ipsub) {
                                    $locid = $loc['location_code'];
                                    break;
                                }
                            }
                        }

                        /*
                         * Now create the relations
                         */
                        echo "IP maps to the location $locid, creating a relation to it\n";
                        $queryTemplate = "MATCH (hardware:Hardware),(location:Location { location_code: {locid} }) " .
                            "WHERE id(hardware)={hardwareid} ".
                            "MERGE (hardware)-[r:LOCATED_AT]->(location)";
                        echo "cypher: $queryTemplate\n";
                        $cypher = new Query($neo4j, $queryTemplate, array('hardwareid'=> $hardware_node->getId(), 'locid' => $hardware_node->$locid));
                        $results = $neo4j->executeCypherQuery($cypher);
                    }
                }

                if (++$rowcnt >= $maxrows) {
                    echo "=STOP=\n";
                    break;
                }
            }

		return 0;
	}

}

