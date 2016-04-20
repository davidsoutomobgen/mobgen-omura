<?php

namespace api\modules\v1\controllers;

use Yii;
//use backend\models\Builds;
//use backend\models\OtaProjects;
//use backend\models\OtaProjectsBuildtypes;
//use common\models\User;
//use common\models\LoginForm;

use yii\rest\Controller;
//use yii\rest\ActiveController;
//use yii\data\ActiveDataProvider;
use yii\web\Response;
use yii\helpers\ArrayHelper;

use Everyman\Neo4j\Client;
use Everyman\Neo4j\Cypher\Query;


/**
 * Builds Controller API
 *
 * @author Sascha Fr�mbs <sascha@mobgen.com>
 */
//class WhoiswhoController extends ActiveController
class WhoiswhoController extends Controller
{
//    public $modelClass = 'api\modules\v1\models\Builds';
//    public $prepareDataProvider;

	//With this behavior only show json format
	public function behaviors()
	{
		return ArrayHelper::merge(parent::behaviors(), [
			[
				'class' => 'yii\filters\ContentNegotiator',
				//'only' => ['view', 'index'],  // in a controller
				// if in a module, use the following IDs for user actions
				// 'only' => ['user/view', 'user/index']
				'formats' => [
					'application/json' => Response::FORMAT_JSON,
				],
			],
		]);
	}

	public function actionIndex()
	{
        $items = array('dataset' => 'info', 'count' => 1, 'status' => 'ok', 'data' => array(
            'api_name' => 'whoiswho',
            'api_version' => '1.0.0',
            'api_owner' => 'Mobgen BV',
            'api_copyright' => '&copy; Copyright 2016 by Mobgen BV',    // �
            'owner_website' => 'http://www.mobgen.com',
        ));
		return $items;
	}

	public function actionPersons()
	{
        /*
         * Set up the neo4j connection
         */
        $neo4j = new Client();
        $neo4j->getTransport()->setAuth('neo4j','none');

        $queryTemplate = "MATCH (n:User) RETURN n ORDER BY n.first_name";
        $cypher = new Query($neo4j, $queryTemplate);
        $results = $cypher->getResultSet();
        $ncnt = count($results);

        $nodes = array('dataset' => 'person', 'count' => $ncnt, 'status' => 'ok', 'data' => array());
        foreach ($results as $row) {
            $nodes['data'][] = array(
                'person_code' =>  $row['n']->person_code,
//				'person_guide' =>  $row['n']->person_guide,
				'name' => $row['n']->name,
                'salutation' => $row['n']->salutation,
				'full_name' =>  $row['n']->full_name,
				'first_name' =>  $row['n']->first_name,
				'family_name' =>  $row['n']->family_name,
				'family_name_prefix' => $row['n']->family_name_prefix,
                'middle_name' => $row['n']->middle_name,
				'known_as' =>  $row['n']->known_as,
				'gender' =>  $row['n']->gender,
//				'date_of_birth' =>  $row['n']->date_of_birth,
				'birthday' =>  substr($row['n']->date_of_birth,5,5),
				'country_of_birth' =>  $row['n']->country_of_birth,
				'city_of_birth' =>  $row['n']->city_of_birth,
				'country_of_residence' =>  $row['n']->country_of_residence,
				'active_employee' =>  $row['n']->active_employee,
				'student' =>  $row['n']->student,
				'nationality' =>  $row['n']->nationality,
				'primary_language' =>  $row['n']->primary_language,
//				'position_code' =>  $row['n']->position_code,
				'position_title' =>  $row['n']->position_title,
				'manager_of_unit' =>  $row['n']->manager_of_unit,
//				'number_of_subordinates' =>  $row['n']->number_of_subordinates,
				'org_unit_code' =>  $row['n']->org_unit_code,
				'org_unit_name' =>  $row['n']->org_unit_name,
//				'effective_from' =>  $row['n']->effective_from,
				'company_name' =>   $row['n']->company_name,
				'location_name' =>   $row['n']->location_name,
//				'deployment_effective_from' =>   $row['n']->deployment_effective_from,
				'last_modified_date' =>   $row['n']->last_modified_date,
				'work_number' =>   $row['n']->work_number,
				'mobile_number' =>   $row['n']->mobile_number,
				'internal_email' =>   $row['n']->internal_email,
				'skype_name' =>  $row['n']->skype_name,
				'messenger' =>  $row['n']->messenger,
				'image_filename' => isset($row['n']->image_filename) ? $row['n']->image_filename : '',
            );
        }

        return $nodes;
	}

	public function actionOrgUnits()
	{
		/*
		 * Set up the neo4j connection
		 */
		$neo4j = new Client();
		$neo4j->getTransport()->setAuth('neo4j','none');

        $queryTemplate = "MATCH (n:OrgUnit) RETURN n ORDER BY n.sort_order";
		$cypher = new Query($neo4j, $queryTemplate);
		$results = $cypher->getResultSet();
        $ncnt = count($results);

        $nodes = array('dataset' => 'org-units', 'count' => $ncnt, 'status' => 'ok', 'data' => array());
        foreach ($results as $row) {
            $nodes['data'][] = array(
                'org_unit_code' => $row['n']->org_unit_code,
                'org_unit_name' => $row['n']->org_unit_name,
                'sort_order' => $row['n']->sort_order,
                'parent_org_unit_code' => $row['n']->parent_org_unit_code,
                'manager_of_org_unit_person_code' => $row['n']->manager_of_org_unit_person_code,
                'effective_from' => $row['n']->effective_from,
                'effective_to' => $row['n']->effective_to,
            );
        }

        return $nodes;
	}
}


