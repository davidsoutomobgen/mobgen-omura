<?php

/**
 * @author sfraembs
 *
 * @desc the console parse command with sdengine xml config implementation.
 *
 */
namespace console\controllers;

use Yii;
use yii\console\Controller,
	Everyman\Neo4j\Client,
	Everyman\Neo4j\Cypher\Query;

class CezanneController extends Controller
{
	public $userid;

	public function options()
	{
		return ['userid'];
	}
	public function optionAliases()
	{
		return ['id' => 'userid'];
	}

//	app function beforeAction($action, $params) {
//		return parent::beforeAction($action, $params);
//	}

	// Default action, in the yii code it looked like it would be called defaultAction()
	public function actionIndex() {
		echo "::actionIndex()\n";
		Yii::info("argv[0]=" . var_export($_SERVER['argv'],true) . "\n");
		Yii::info("testing userid: $this->userid\n");

		$limit = 10;
		$neo4j = new Client();
		$neo4j->getTransport()->setAuth('neo4j','none');

		$queryTemplate =
<<<QUERY
MATCH (m:Movie)<-[:ACTED_IN]-(a:Person)
 RETURN m.title as movie, collect(a.name) as cast
 LIMIT {limit}
QUERY;

		$cypher = new Query($neo4j, $queryTemplate, array('limit'=>$limit));
		$results = $cypher->getResultSet();

		$actors = [];
		$nodes = [];
		$rels = [];
		foreach ($results as $result) {
			$target = count($nodes);
			$nodes[] = array('title' => $result['movie'], 'label' => 'movie');

			foreach ($result['cast'] as $name) {
				if (!isset($actors[$name])) {
					$actors[$name] = count($nodes);
					$nodes[] = array('title' => $name, 'label' => 'actor');
				}
				$rels[] = array('source' => $actors[$name], 'target' => $target);
			}
		}
		echo "movies: ". var_export($nodes, true) ."\n";
		echo "rels: ". var_export($rels, true) ."\n";
		echo "actors: ". var_export($actors, true) ."\n";


//		return 0; // This is returned by default (i think)
	}

	public function actionYeah()
	{
		echo "::actionYeah()\n";
		return Controller::EXIT_CODE_NORMAL;
//		return Controller::EXIT_CODE_ERROR;
	}


	public function curlRequest($url, $headers='')
	{
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//		curl_setopt($curl, CURLOPT_VERBOSE, 1);
//		curl_setopt($curl, CURLOPT_HEADER, 1);
		if ($headers && !empty($headers)) {
			$h = array();
			foreach ($headers as $key => $val) {
				$h[] = $key . ': ' . $val;
			}
			curl_setopt($curl, CURLOPT_HTTPHEADER, $h);
		}

		$curlResponse = curl_exec($curl);

//		$curlinfo = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
//		$httpresultcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$curlinfo = curl_getinfo($curl);
//		echo var_dump($curlinfo, true) ."\n\n";
		curl_close($curl);

		$curlinfo['response'] = $curlResponse;

		return $curlinfo;
	}

    public function actionCreateLocations()
    {
        echo "actionCreateLocations()\n";

        $alocations = [
            ['location_code' => 'KK', 'name' => 'Unknown', 'country' => 'Unknown', 'city' => 'Unknown', 'subnet' => '0.0.0.0', 'subnet_mask' => '255.255.255.255'],
            ['location_code' => 'AMS', 'name' => 'Amsterdam', 'country' => 'The Netherlands', 'city' => 'Amsterdam', 'subnet' => '192.168.0.0', 'subnet_mask' => '255.255.240.0'],
            ['location_code' => 'COR', 'name' => 'La Coruna', 'country' => 'Spain', 'city' => 'La Coruna', 'subnet' => '192.168.16.0', 'subnet_mask' => '255.255.240.0'],
            ['location_code' => 'MAL', 'name' => 'Malaga', 'country' => 'Spain', 'city' => 'Malaga', 'subnet' => '0.0.0.0', 'subnet_mask' => '255.255.255.255'],
            ['location_code' => 'LON', 'name' => 'London', 'country' => 'United Kingdom', 'city' => 'London', 'subnet' => '0.0.0.0', 'subnet_mask' => '255.255.255.255'],
        ];

        $neo4j = new Client();
        $neo4j->getTransport()->setAuth('neo4j','none');

        foreach ($alocations as $loc) {
            echo "Create Location for {$loc['name']}\n";

            $queryTemplate = "MERGE (location:Location { location_code: {loccode} }) " .
                "ON MATCH SET location = {props} ";
                "ON CREATE SET location = {props}";
            $cypher = new Query($neo4j, $queryTemplate, array('loccode'=> $loc['location_code'], 'props' => $loc));
            $results = $neo4j->executeCypherQuery($cypher);
        }
    }

	public function actionImportDepartments()
	{
		echo "actionImportDepartments()\n";

		$headers = Yii::$app->params['cezanne_api']['headers'];
		$apibaseurl = Yii::$app->params['cezanne_api']['url'];

		$url = $apibaseurl . "OrgUnits";
		$curlinfo = $this->curlRequest($url, $headers);

		/*
		 * Get the user from Cezanne
		 */
		if ($curlinfo['http_code'] != 200) {
			echo "Did not get a 200 return code, failed import.\n";
			echo "http: ". $curlinfo['http_code'] ."\n";
			return 1;
		}

		/*
		 * Set up the neo4j connection
		 */
		$neo4j = new Client();
		$neo4j->getTransport()->setAuth('neo4j','none');

//		echo "curlResponse: {$curlinfo['response']}\n";
		$orgunitdata = json_decode($curlinfo['response']);
		foreach ($orgunitdata->value as $key => $orgunit) {
			echo "OrgUnitName: " . $orgunit->OrgUnitName . "\n";

			/*
			 * Lets create the OrgUnit in the DB is not yet exist
			 */
			echo "Create a OrgUnit in the DB\n";
			$props = array(
				'org_unit_code' => $orgunit->OrgUnitCode,
				'org_unit_name' => $orgunit->OrgUnitName != null ? $orgunit->OrgUnitName : '',
				'sort_order' => $orgunit->SortOrder != null ? $orgunit->SortOrder : 0,
				'parent_org_unit_code' => $orgunit->ParentOrgUnitCode != null ? $orgunit->ParentOrgUnitCode : '',
				'manager_of_org_unit_person_code' => $orgunit->ManagerOfOrgUnitPersonCode != null ? $orgunit->ManagerOfOrgUnitPersonCode : '',
				'effective_from' => $orgunit->EffectiveFrom != null ? $orgunit->EffectiveFrom : '0000-00-00T00:00:00',
				'effective_to' => $orgunit->EffectiveTo != null ? $orgunit->EffectiveTo : '0000-00-00T00:00:00',
			);

			$queryTemplate = "MERGE (orgunit:OrgUnit { org_unit_code: {orgunitcode} }) " .
				"ON CREATE SET orgunit = {props}";
			$cypher = new Query($neo4j, $queryTemplate, array('orgunitcode'=> $orgunit->OrgUnitCode, 'props' => $props));
			$results = $neo4j->executeCypherQuery($cypher);
		}

		/*
		 * Now create the relations
		 */
		foreach ($orgunitdata->value as $key => $orgunit) {
			echo "OrgUnitName: " . $orgunit->OrgUnitName . "\n";

			// We only need to create a relationship if there is one of course
			if ($orgunit->ParentOrgUnitCode != null) {
				$queryTemplate = "MATCH (org1:OrgUnit { org_unit_code: {child} }),(org2:OrgUnit { org_unit_code: {parent} }) " .
					"MERGE (org1)-[r:CHILD_OF]->(org2)";
				$cypher = new Query($neo4j, $queryTemplate, array('child'=> $orgunit->OrgUnitCode, 'parent' => $orgunit->ParentOrgUnitCode));
				$results = $neo4j->executeCypherQuery($cypher);
			}
		}

	}

	public function actionImportImage()
	{
		echo "actionImportUser('$this->userid')\n";

		$headers = Yii::$app->params['cezanne_api']['headers'];
		$apibaseurl = Yii::$app->params['cezanne_api']['url'];

		$url = $apibaseurl . "People('$this->userid')";
		$curlinfo = $this->curlRequest($url, $headers);

		/*
		 * Get the user from Cezanne
		 */
		if ($curlinfo['http_code'] != 200) {
			echo "Did not get a 200 return code, skipping remaining import.\n";
			echo "http: " . $curlinfo['http_code'] . "\n";
			return 1;
		}
//		echo "curlResponse: {$curlinfo['response']}\n";
		$userdata = json_decode($curlinfo['response']);
		echo "FullName: " . $userdata->FullName . "\n";
		if ($userdata->ActiveEmployee == 0) {
			echo "Employee no longer active.\n";
			return 0;
		}
		if (strlen($userdata->Picture) < 10) {
			echo "No image available.\n";
			return 0;
		}

		/*
		 * Create the picture on the server extracted from the base64 data
		 */
		$imagename = 'cezanne_image_'. md5($userdata->FullName);
		$mediapath = realpath(Yii::$app->basePath ."/../frontend/web/whoiswho");
		echo "Picture: " . $userdata->Picture . "\n";
		echo "FullName: " . $userdata->FullName . "\n";
		echo "base path: " . Yii::$app->basePath . "\n";
		echo "media path: " . $mediapath . "\n";

		$format = 'jpg';
		$image = base64_decode($userdata->Picture);
//		$npos = strpos($image, ',');
//		if ($npos) {
//			$format = substr($image, 0, $npos);
//			$image = substr($image, $npos+1);
//		}
		$filename = $imagename .'.jpg';
		echo "fortmat: $format\n";
		echo "image name: " . $filename . "\n";
		file_put_contents($mediapath .'/'. $filename, $image);

		/*
		 * Set up the neo4j connection
		 */
		$limit = 10;
		$neo4j = new Client();
		$neo4j->getTransport()->setAuth('neo4j','none');

		$datetime = date("Y-m-d\TH:i:s", time());
		echo "datetime: $datetime\n";

		$queryTemplate = "MATCH (user:User { person_code: {userid} }) ".
			"SET user.image_filename={imagename},user.image_update_date={upddate}";
		$cypher = new Query($neo4j, $queryTemplate, array('userid'=> $userdata->PersonCode, 'imagename' => $filename, 'upddate' => $datetime));
		$results = $neo4j->executeCypherQuery($cypher);

	}

	public function actionImportUser()
	{
		echo "actionImportUser('$this->userid')\n";

		$headers = Yii::$app->params['cezanne_api']['headers'];
		$apibaseurl = Yii::$app->params['cezanne_api']['url'];

		$url = $apibaseurl . "People('$this->userid')";
		$curlinfo = $this->curlRequest($url, $headers);

		/*
		 * Get the user from Cezanne
		 */
		if ($curlinfo['http_code'] != 200) {
			echo "Did not get a 200 return code, skipping remaining import.\n";
			echo "http: ". $curlinfo['http_code'] ."\n";
			return 1;
		}
//		echo "curlResponse: {$curlinfo['response']}\n";
		$userdata = json_decode($curlinfo['response']);
		echo "FullName: ". $userdata->FullName ."\n";
		if ($userdata->ActiveEmployee == 0) {
			echo "Employee no longer active.\n";
			return 0;
		}


		/*
		 * Fetch the bio and login name from the comments field in the users personal data.
		 */
		$usr_login = '';
		$usr_bio = '';
		if(isset($userdata->Comments)) {
			$regex = '#<\s*?login\b[^>]*>(.*?)</login\b[^>]*>#s';
			if (preg_match($regex, (string)$userdata->Comments, $matches))
				$usr_login = $matches[1];

			$regex = '#<\s*?bio\b[^>]*>(.*?)</bio\b[^>]*>#s';
			if (preg_match($regex, (string)$userdata->Comments, $matches))
				$usr_bio = $matches[1];
//			echo "regex result: " . var_export($matches, true) . "\n";
		}
//		echo "usr_login: $usr_login - usr_bio: $usr_bio\n";
//		return 0;
		if (empty($usr_login) || empty($usr_bio)) {
			echo "Warning! - missing login or bio data from this user!\n";
		}

		/*
		 * Get the employee search data
		 */
//		$url = $apibaseurl . "People('$this->userid')/PeopleToOrgUnitPositionDeploymentCurrentPrimary";
//		$url = $apibaseurl . "People('$this->userid')/PeopleToOrgUnitPositionDeploymentsAll";
		$url = $apibaseurl . "ActiveEmployeesSearch('$this->userid')";
		$curlinfo = $this->curlRequest($url, $headers);
		if ($curlinfo['http_code'] != 200) {
			echo "Did not get a 200 return code, skipping remaining import.\n";
			echo "http: ". var_export($curlinfo,true) ."\n";
			return 1;
		}
		$usersearcharray = json_decode($curlinfo['response']);
//		echo "userorgdata: ". var_export($usersearcharray->value,true) ."\n";
//		if (!isset($usersearcharray->value[0]->OrgUnitCode)) {
		if (!isset($usersearcharray->OrgUnitCode_Search)) {
			echo "No organisation data available for user\n";
			return 1;
		}
//		$usersearchdata = $usersearcharray->value[0];
		$usersearchdata = $usersearcharray;
		echo "OrgName: ". $usersearchdata->OrgUnitName_Search ."\n";
//		echo "userorgdata: ". var_export($usersearchdata,true) ."\n";


		/*
		 * Get the communication data
		 */
		$url = $apibaseurl . "Communications('$this->userid')";
		$curlinfo = $this->curlRequest($url, $headers);
		if ($curlinfo['http_code'] != 200) {
			echo "Did not get a 200 return code, skipping remaining import.\n";
			echo "http: ". var_export($curlinfo,true) ."\n";
			return 1;
		}
//		echo "curlResponse: {$curlinfo['response']}\n";
		$communicationdata = json_decode($curlinfo['response']);
		echo "InternalEmail: ". $communicationdata->InternalEmail ."\n";


		/*
		 * Set up the neo4j connection
		 */
		$limit = 10;
		$neo4j = new Client();
		$neo4j->getTransport()->setAuth('neo4j','none');

//		$queryTemplate = "MATCH (m:Movie)<-[:ACTED_IN]-(a:Person) ".
//			"RETURN m.title as movie, collect(a.name) as cast ".
//			"LIMIT {limit}";

		/*
		 * Check if we have this user in our neo4j DB
		 */
		$queryTemplate = "MATCH (user:User) WHERE user.person_code = {userid} RETURN user";
		$cypher = new Query($neo4j, $queryTemplate, array('userid'=>$this->userid, 'limit'=>$limit));
		$results = $cypher->getResultSet();
//		if ($results->count() == 0) {
		if (true) {
			/*
			 * User does not exist, lets create one.
			 */
			echo "Create a user in neo4j DB\n";
			$props = array(
				'person_code' => $userdata->PersonCode,
				'person_guide' => $userdata->PersonGUID,
				'name' => $userdata->FullName != null ? $userdata->FullName : '',	// So something shows up in the UI
				'salutation' => $userdata->Salutation != null ? $userdata->Salutation : '',
				'full_name' => $userdata->FullName != null ? $userdata->FullName : '',
				'first_name' => $userdata->FirstName != null ? $userdata->FirstName : '',
				'family_name' => $userdata->FamilyName != null ? $userdata->FamilyName : '',
				'family_name_prefix' => $userdata->FamilyNamePrefix != null ? $userdata->FamilyNamePrefix : '',
				'middle_name' => $userdata->MiddleName != null ? $userdata->MiddleName : '',
				'known_as' => $userdata->KnownAs != null ? $userdata->KnownAs : '',
				'gender' => $userdata->Gender != null ? $userdata->Gender : '',
				'date_of_birth' => $userdata->DateOfBirth != null ? $userdata->DateOfBirth : '',
				'country_of_birth' => $userdata->CountryOfBirth != null ? $userdata->CountryOfBirth : '',
				'city_of_birth' => $userdata->CityOfBirth != null ? $userdata->CityOfBirth : '',
				'country_of_residence' => $userdata->CountryOfResidence != null ? $userdata->CountryOfResidence : '',
				'active_employee' => $userdata->ActiveEmployee != null ? $userdata->ActiveEmployee : 0,
				'student' => $userdata->Student != null ? $userdata->Student : 0,
				'nationality' => $userdata->Nationality != null ? $userdata->Nationality : '',
				'primary_language' => $userdata->PrimaryLanguage != null ? $userdata->PrimaryLanguage : '',
//				'position_code' => $usersearchdata->PositionCode != null ? $usersearchdata->PositionCode : '',
				'company_name' => $usersearchdata->CompanyName_Search != null ? $usersearchdata->CompanyName_Search : '',
				'location_name' => $usersearchdata->LocationName_Search != null ? $usersearchdata->LocationName_Search : '',
				'position_title' => $usersearchdata->PositionTitle_Search != null ? $usersearchdata->PositionTitle_Search : '',
				'manager_of_unit' => $usersearchdata->IsManagerOfUnit_Search != null ? $usersearchdata->IsManagerOfUnit_Search : 0,
//				'number_of_subordinates' => $usersearchdata->NumberOfSubordinates != null ? $usersearchdata->NumberOfSubordinates : 0,
				'org_unit_code' => $usersearchdata->OrgUnitCode_Search != null ? $usersearchdata->OrgUnitCode_Search : '',
				'org_unit_name' => $usersearchdata->OrgUnitName_Search != null ? $usersearchdata->OrgUnitName_Search : '',
				'deployment_effective_from' => $usersearchdata->DeploymentEffectiveFrom_Search != null ? $usersearchdata->DeploymentEffectiveFrom_Search : '0000-00-00T00:00:00',
				'last_modified_date' => $userdata->LastModifiedDate != null ? $userdata->LastModifiedDate : '0000-00-00T00:00:00',
				'work_number' => $communicationdata->WorkNumber != null ? $communicationdata->WorkNumber : '',
				'mobile_number' => $communicationdata->MobileNumber != null ? $communicationdata->MobileNumber : '',
				'internal_email' => $communicationdata->InternalEmail != null ? $communicationdata->InternalEmail : '',
				'skype_name' => $communicationdata->SkypeName != null ? $communicationdata->SkypeName : '',
				'messenger' => $communicationdata->Messenger != null ? $communicationdata->Messenger : '',
				'network_name' => $usr_login,
				'bio' => $usr_bio,
			);

//			$queryTemplate = "CREATE (user:User { props })";
			$queryTemplate = "MERGE (user:User { person_code: {userid} }) ".
								"ON CREATE SET user = {props} ".
								"ON MATCH SET user = {props}";
			$cypher = new Query($neo4j, $queryTemplate, array('userid'=> $userdata->PersonCode, 'props' => $props));
			$results = $neo4j->executeCypherQuery($cypher);

			echo "added: ". $results->count() ."\n";
//			echo "cypher: ". var_export($cypher,true) ."\n";
//			echo "results: ". var_export($results,true) ."\n";

//			echo "cypher obj: ". var_export($cypher,true) ."\n";
//			$results = $cypher->getResultSet();

		} else {
			echo "User found in neo4j\n";
//			echo "neo4j result: ". var_export($results,true) ."\n";
		}

		/*
		 * Now create the relations
		 */
		// We only need to create a relationship if there is one of course
		if ($usersearchdata->OrgUnitCode_Search != null) {
//			if ($userdata->ManagerOfUnit == 1) {
			if ($usersearchdata->IsManagerOfUnit_Search == 1) {
				echo "Manager of: " . $usersearchdata->OrgUnitCode_Search . "\n";
				$queryTemplate = "MATCH (user:User { person_code: {userid} }),(org:OrgUnit { org_unit_code: {orgunitid} }) " .
					"MERGE (user)-[r:MANAGER_OF]->(org)";
			} else {
				echo "Works in: " . $usersearchdata->OrgUnitCode_Search . "\n";
				$queryTemplate = "MATCH (user:User { person_code: {userid} }),(org:OrgUnit { org_unit_code: {orgunitid} }) " .
					"MERGE (user)-[r:WORKS_IN]->(org)";
			}
			$cypher = new Query($neo4j, $queryTemplate, array('userid'=> $userdata->PersonCode, 'orgunitid' => $usersearchdata->OrgUnitCode_Search));
			$results = $neo4j->executeCypherQuery($cypher);
		}

		return 0;

	}


	public function actionImportAllUsers()
	{
		echo "actionImportAllUsers()\n";

		$headers = Yii::$app->params['cezanne_api']['headers'];
		$apibaseurl = Yii::$app->params['cezanne_api']['url'];

		$url = $apibaseurl . "People()";
		$curlinfo = $this->curlRequest($url, $headers);

		/*
		 * Get the user from Cezanne
		 */
		if ($curlinfo['http_code'] != 200) {
			echo "Did not get a 200 return code, skipping remaining import.\n";
			echo "http: " . $curlinfo['http_code'] . "\n";
			return 1;
		}
		$userdata = json_decode($curlinfo['response']);

		/*
		 * Now create the relations
		 */
		foreach ($userdata->value as $key => $user) {
			echo "FullName: " . $user->FullName . "\n";
			if ($user->ActiveEmployee == 0) {
				echo "Skipping import: employee no longer active.\n";
			} else {
				$this->userid = $user->PersonCode;
				$this->actionImportUser();
			}
		}

		return 0;
	}

	public function actionImportAllImages()
	{
		echo "actionImportAllImages()\n";

		$headers = Yii::$app->params['cezanne_api']['headers'];
		$apibaseurl = Yii::$app->params['cezanne_api']['url'];

		$url = $apibaseurl . "People()";
		$curlinfo = $this->curlRequest($url, $headers);

		/*
		 * Get the user from Cezanne
		 */
		if ($curlinfo['http_code'] != 200) {
			echo "Did not get a 200 return code, skipping remaining import.\n";
			echo "http: " . $curlinfo['http_code'] . "\n";
			return 1;
		}
		$userdata = json_decode($curlinfo['response']);

		/*
		 * Now create the relations
		 */
		foreach ($userdata->value as $key => $user) {
			echo "FullName: " . $user->FullName . "\n";
			if ($user->ActiveEmployee == 0) {
				echo "Skipping import: employee no longer active.\n";
			} else {
				$this->userid = $user->PersonCode;
				$this->actionImportImage();
			}
		}

		return 0;
	}



	public function actionTest()
	{
		$neo4j = new Client();
		$neo4j->getTransport()->setAuth('neo4j','none');

		$queryTemplate = "MATCH (n:OrgUnit) RETURN n ORDER BY n.sort_order";

		$cypher = new Query($neo4j, $queryTemplate);
		$results = $cypher->getResultSet();
//		$results = $neo4j->executeCypherQuery($cypher);

		$nodes = [];
		foreach ($results as $row) {

			$nodes[] = array(
				'org_unit_code' => $row['n']->org_unit_code,
				'org_unit_name' => $row['n']->org_unit_name,
				'sort_order' => $row['n']->sort_order,
				'parent_org_unit_code' => $row['n']->parent_org_unit_code,
				'manager_of_org_unit_person_code' => $row['n']->manager_of_org_unit_person_code,
				'effective_from' => $row['n']->effective_from,
				'effective_to' => $row['n']->effective_to,
			);
		}

		echo "nodes: ". var_export($nodes,true) ."\n";
		return 0;
	}

}

