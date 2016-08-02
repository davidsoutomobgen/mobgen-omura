<?php

namespace api\modules\v1\controllers;

use Yii;
//use backend\models\Builds;
//use backend\models\OtaProjects;
//use backend\models\OtaProjectsBuildtypes;
//use common\models\User;
//use common\models\LoginForm;
use yii\filters\auth\HttpBasicAuth;

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

/*	public function init()
	{
		parent::init();
		Yii::$app->user->enableSession = false;
	}
*/
/*	public function behaviors()
	{
		$behaviors = parent::behaviors();
		$behaviors['authenticator'] = [
			'class' => HttpBasicAuth::className(),
		];
		return $behaviors;
	}
	public function authenticate ( $user, $request, $response )
	{
		return null;
	}

	public function findIdentityByAccessToken()
	{

	}
*/

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
            // @SAS: If I enable this a basic http authentication is presented.
            //  I think we need to create our own auth class (or derive) to handle a api token passed in the http header
//			'authenticator' => [
//				'class' => HttpBasicAuth::className(),
//			],
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

    public function actionLocations()
    {
        /*
         * Set up the neo4j connection
         */
        $neo4j = new Client();
        $neo4j->getTransport()->setAuth('neo4j','none');

        $queryTemplate = "MATCH (n:Location) RETURN n ORDER BY n.name";
        $cypher = new Query($neo4j, $queryTemplate);
        $results = $cypher->getResultSet();
        $ncnt = count($results);

        $nodes = array('dataset' => 'locations', 'count' => $ncnt, 'status' => 'ok', 'data' => array());
        foreach ($results as $row) {
            $nodes['data'][] = array(
                'person_code' =>  $row['n']->person_code,
                'image_filename' => isset($row['n']->image_filename) ? $row['n']->image_filename : '',
            );
        }

        return $nodes;
    }

	public function actionPersons()
	{
        /*
         * Set up the neo4j connection
         */
        $neo4j = new Client();
        $neo4j->getTransport()->setAuth('neo4j','none');

//      $queryTemplate = "MATCH (n:User) RETURN n ORDER BY n.first_name";
        $queryTemplate = "MATCH (n:User {active_employee: 1}) RETURN n ORDER BY n.first_name";
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
				'active_employee' => $row['n']->active_employee,
				'student' => $row['n']->student,
				'nationality' => $row['n']->nationality,
				'primary_language' => $row['n']->primary_language,
//				'position_code' => $row['n']->position_code,
				'position_title' => $row['n']->position_title,
				'manager_of_unit' => $row['n']->manager_of_unit,
//				'number_of_subordinates' => $row['n']->number_of_subordinates,
				'org_unit_code' => $row['n']->org_unit_code,
				'org_unit_name' => $row['n']->org_unit_name,
//				'effective_from' => $row['n']->effective_from,
				'company_name' => $row['n']->company_name,
				'location_name' => $row['n']->location_name,
				'deployment_effective_from' => $row['n']->deployment_effective_from,
				'last_modified_date' => $row['n']->last_modified_date,
				'work_number' => $row['n']->work_number,
				'mobile_number' => $row['n']->mobile_number,
				'internal_email' => $row['n']->internal_email,
				'skype_name' => $row['n']->skype_name,
				'messenger' => $row['n']->messenger,
				'public_bio' => isset($row['n']->bio) ? $row['n']->bio : 'PLEASE ADD A BIO!! - Morbi in ligula ac erat maximus ultrices. Nullam auctor a erat vitae accumsan. Praesent ultrices semper sem non gravida. Etiam rhoncus facilisis libero, id suscipit nisl luctus sit amet. Maecenas pharetra congue ex a commodo. Vivamus at posuere urna. Duis est mauris, pulvinar ac quam vel, vestibulum scelerisque nisl. Ut cursus fermentum metus eget mollis. Morbi a dui at magna sagittis ultricies nec in massa. Etiam et est auctor, molestie ipsum ut, eleifend odio. Praesent vel molestie nulla. Donec porttitor, ipsum in sodales convallis, massa lectus dictum neque, vestibulum posuere nisi velit vitae tellus. Cras vitae facilisis eros, rutrum mollis libero. Integer non felis ligula.Morbi in ligula ac erat maximus ultrices. Nullam auctor a erat vitae accumsan. Praesent ultrices semper sem non gravida. Etiam rhoncus facilisis libero, id suscipit nisl luctus sit amet. Maecenas pharetra congue ex a commodo. Vivamu',
				'image_filename' => isset($row['n']->image_filename) ? $row['n']->image_filename : '',
				'image_update_date' => isset($row['n']->image_update_date) ? $row['n']->image_update_date : '0000-00-00T00:00:00',
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

	public function actionCalendar($from='',$to='')
	{
		/*
         * Set up the neo4j connection
         */
		$neo4j = new Client();
		$neo4j->getTransport()->setAuth('neo4j','none');

		if (!empty($from)) {
			$ts_from = strtotime($from);
		} else {
			$ts_from = time();
		}
		if (!empty($to)) {
			$ts_to = strtotime($to);
		} else {
//			$ts_to = $ts_from + (86400 * 30);   // 30 days from today
			$last_day = date('t', $ts_from);
			$month = date('m', $ts_from);
			$year = date('Y', $ts_from) + 1;    // We add one year
			$ts_to = mktime(null,null,null,$month,$last_day,$year);
//			$ts_to = mktime(23,59,59,$month,$last_day,$year);
		}

		$date_from = date("Y-m-d\T00:00:00", $ts_from);
		$date_to = date("Y-m-d\T23:59:59", $ts_to);

		/*
		 * Check if to date is after from date
		 */
		if ($ts_to < $ts_from) {
			// @TODO: Implement propper REST jsson error
			echo "Error: from date is after to date - from: $date_from - to: $date_to";
			return;
		}

		$queryTemplate = "MATCH (n:AbsenceEvent {absence_type_name: 'Holiday'})-[:EVENT_FOR]->(u:User)".
			" WHERE (n.effective_to >= '$date_from') AND (n.effective_from <= '$date_to')".
			" RETURN n,u".
			" ORDER BY n.effective_from";
		$cypher = new Query($neo4j, $queryTemplate);
		$results = $cypher->getResultSet();

		$ncnt = count($results);

		$nodes = array('dataset' => 'calendar', 'count' => $ncnt, 'status' => 'ok', 'data' => array());
		foreach ($results as $row) {
			$nodes['data'][] = array(
                'event_guid' => $row['n']->person_absence_event_guid,
				'person_code' => $row['n']->person_code,
				'person_name' => $row['u']->name,
				'image_filename' => isset($row['u']->image_filename) ? $row['u']->image_filename : '',
				'org_unit_code' => $row['u']->org_unit_code,

				'event_name' => $row['n']->absence_reason,
//				'event_status' => $row['n']->absence_status,
				'effective_from' => $row['n']->effective_from,
				'effective_to' => $row['n']->effective_to,

//				'absence_plan_type_name' => $row['n']->absence_plan_type_name,
//				'absence_type_name' => $row['n']->absence_type_name,
//				'absence_category' => $row['n']->absence_category,

				'commences_code' => $row['n']->commences_code,
				'commences' => $row['n']->commences,
				'finishes_code' => $row['n']->finishes_code,
				'finishes' => $row['n']->finishes,

				'total_time' => $row['n']->total_working_time_taken_by_time_unit,
				'time_units' => $row['n']->time_units,

//				'time_units' => $row['n']->time_units,
//				'total_by_time_unit' => $row['n']->total_by_time_unit,
//				'total_days' => $row['n']->total_days,
//				'total_working_time_taken_by_time_unit' => $row['n']->total_working_time_taken_by_time_unit,
//				'absence_event_total_working_hours' => $row['n']->absence_event_total_working_hours,
			);
		}

		return $nodes;
	}

}


