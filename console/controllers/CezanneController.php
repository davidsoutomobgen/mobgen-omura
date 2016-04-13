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

		$queryTemplate = "MATCH (user:User { person_code: {userid} }) ".
			"SET user.image_filename = {imagename}";
		$cypher = new Query($neo4j, $queryTemplate, array('userid'=> $userdata->PersonCode, 'imagename' => $filename));
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
		 * Get the organization unit data
		 */
//		$url = $apibaseurl . "People('$this->userid')/PeopleToOrgUnitPositionDeploymentCurrentPrimary";
		$url = $apibaseurl . "People('$this->userid')/PeopleToOrgUnitPositionDeploymentsAll";
		$curlinfo = $this->curlRequest($url, $headers);
		if ($curlinfo['http_code'] != 200) {
			echo "Did not get a 200 return code, skipping remaining import.\n";
			echo "http: ". var_export($curlinfo,true) ."\n";
			return 1;
		}
		$userorgarray = json_decode($curlinfo['response']);
//		echo "userorgdata: ". var_export($userorgarray->value,true) ."\n";
		if (!isset($userorgarray->value[0]->OrgUnitCode)) {
			echo "No organisation data available for user\n";
			return 1;
		}
		$userorgdata = $userorgarray->value[0];
		echo "OrgName: ". $userorgdata->OrgUnitName ."\n";
//		echo "userorgdata: ". var_export($userorgdata,true) ."\n";


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
				'full_name' => $userdata->FullName != null ? $userdata->FullName : '',
				'first_name' => $userdata->FirstName != null ? $userdata->FirstName : '',
				'family_name' => $userdata->FamilyName != null ? $userdata->FamilyName : '',
				'know_nas' => $userdata->KnownAs != null ? $userdata->KnownAs : '',
				'gender' => $userdata->Gender != null ? $userdata->Gender : '',
				'date_of_birth' => $userdata->DateOfBirth != null ? $userdata->DateOfBirth : '',
				'city_of_birth' => $userdata->CityOfBirth != null ? $userdata->CityOfBirth : '',
				'active_employee' => $userdata->ActiveEmployee != null ? $userdata->ActiveEmployee : 0,
				'student' => $userdata->Student != null ? $userdata->Student : 0,
				'nationality' => $userdata->Nationality != null ? $userdata->Nationality : '',
				'primary_language' => $userdata->PrimaryLanguage != null ? $userdata->PrimaryLanguage : '',
				'position_code' => $userorgdata->PositionCode != null ? $userorgdata->PositionCode : '',
				'position_title' => $userorgdata->PositionTitle != null ? $userorgdata->PositionTitle : '',
				'manager_of_unit' => $userorgdata->ManagerOfUnit != null ? $userorgdata->ManagerOfUnit : 0,
				'number_of_subordinates' => $userorgdata->NumberOfSubordinates != null ? $userorgdata->NumberOfSubordinates : 0,
				'org_unit_code' => $userorgdata->OrgUnitCode != null ? $userorgdata->OrgUnitCode : '',
				'org_unit_name' => $userorgdata->OrgUnitName != null ? $userorgdata->OrgUnitName : '',
				'effective_from' => $userorgdata->EffectiveFrom != null ? $userorgdata->EffectiveFrom : '0000-00-00T00:00:00',
				);

//			$queryTemplate = "CREATE (user:User { props })";
			$queryTemplate = "MERGE (user:User { person_code: {userid} }) ".
								"ON CREATE SET user = {props}";
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
		if ($userorgdata->OrgUnitCode != null) {
			if ($userorgdata->ManagerOfUnit == 1) {
				echo "Manager of: " . $userorgdata->OrgUnitCode . "\n";
				$queryTemplate = "MATCH (user:User { person_code: {userid} }),(org:OrgUnit { org_unit_code: {orgunitid} }) " .
					"MERGE (user)-[r:MANAGER_OF]->(org)";
			} else {
				echo "Works in: " . $userorgdata->OrgUnitCode . "\n";
				$queryTemplate = "MATCH (user:User { person_code: {userid} }),(org:OrgUnit { org_unit_code: {orgunitid} }) " .
					"MERGE (user)-[r:WORKS_IN]->(org)";
			}
			$cypher = new Query($neo4j, $queryTemplate, array('userid'=> $userdata->PersonCode, 'orgunitid' => $userorgdata->OrgUnitCode));
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



	public function actionTest() {
		echo "::actionTest()\nLOGPATH: ". LOGPATH . "\n";
//		Yii::log('actionTest...', CLogger::LEVEL_INFO, 'mobgen-script');
		Yii::info('actionTest...');
		echo "did we log?\n";
		echo '::getCommandPath(): ' . _app()->getCommandPath() . "\n";
		echo 'basePath: ' . _app()->getBasePath() . "\n";
		echo 'baseUrl: ' . _app()->getBaseUrl() . "\n";
//    	echo 'request: '. _app()->getRequest() ."\n";
		echo 'getRuntimePath(): ' . _app()->getRuntimePath() . "\n";
		echo 'getRuntimePath(): ' . _app()->getRuntimePath() . "\n";
	}

	public function actionDebug() {
		User::sendBadges();
	}

	public function actionTestPush() {
		echo "::actionTestPush()\n";

		$pm = Yii::app()->mgpushmanager;
		$queueId = $pm->createQueue('com.mobgen.nibeapi');
		/*
		  //		$queueId = $pm->createQueue('com.mobgen.shellinno', 'main');
		  //		$queueId = $pm->createQueue('com.mobgen.shellinno', 'background');
		  //		$queueId = $pm->createQueue('com.mobgen.shellinno');
		  $tokenId = $pm->registerToken('com.mobgen.nibeapi', '7141AF4B678461449DBEF98DB9FE55B845CD19BB3891FC5BE5E1FE55E88536AB');
		  //		$tokenId = $pm->registerToken('com.mobgen.shellinno', 'tst12345',
		  //							array('type'=>'android', 'device'=>'LG One', 'deviceos'=>'android') );
		  //		$tokenId = $pm->registerToken('com.mobgen.shellinno', 'APA91bGWikrEjd5moVXcv9NDY6bS60KV1mDGP-f1RlK0T699u-wUaEhsKFKom6Wu4HCBzS9b5JqaNGmFDlc0z09zSLEB7fSUa2rdx9LRW_OwopL0I1gif2RvdqINSjfFvGwVECpa_HCjY5Fr9nOZzgzrtBlDpvU3mg',
		  //							array('type'=>'android', 'device'=>'GT-I9000', 'deviceos'=>'Android-2.3.6') );
		  //		$tokenId = $pm->registerToken('com.mobgen.shellinno', 'APA91bESKRAmmFR_E0AQkiniobtXcBFvV6niTESRZYuVuvo-uLc8y5xKSF-yNZh5_2Pmen5TuyShNJuyMjozeIaPAeEnL4b9UZFm7lM9S8PnsWyfWM3tfDrtoeAq0D4-umtfxUrRIj1GViJ7MwA-kJ2VymNvDGBM5A',
		  //							array('type'=>'android', 'device'=>'GT-I9000', 'deviceos'=>'Android-2.3.6') );

		  $messageId = $pm->getActiveMessage($queueId);
		  if (!$messageId) {
		  $messageId = $pm->createMessage('This is a yii MgPushManager test push message.');
		  echo "Created new message with id $messageId\n";
		  }

		  $pm->pushToQueue($queueId, $tokenId, $messageId);
		 */
		$pm->prepareQueue($queueId);

//		$pm->processQueue($queueId);
//		$pm->processQueues('com.mobgen.shellinno', 'main');
//		$pm->processQueues('com.mobgen.shellinno');
//		echo "queueId: $queueId\n";
	}

	public function actionTestPush2() {
		echo "::actionTestPush2()\n";

		$pm = Yii::app()->mgpushmanager;
		$queueId = $pm->createQueue('com.mobgen.nibeapi');

		if ($tk = $pm->getTokenByRefId(117)) {
			echo "tokenId: $tk->ptkId [$tk->ptkToken]\n";
		}

		$messageId = $pm->getCustomBadgeMessage();
		$pm->pushToQueue($queueId, $tk->ptkId, $messageId, 124, MgPush::MGPUSH_STATUS_PREPARED);
	}

	public function actionProcessQueues($bundleId, $tag = null) {
		$pm = Yii::app()->mgpushmanager;
		$pm->processQueues($bundleId, $tag);
		$error =  $pm->getLastError();
		if (strlen($error) > 0) {
			echo 'last errors: '. $error ."\n";
		}
	}
}

