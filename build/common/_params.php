<?php
return [
    'projectName'       => 'Omura',
    'projectNameShort'  => 'O',
    'adminEmail' => '${env.admin_email}',
    'supportEmail' => '${env.admin_email}',
    'user.passwordResetTokenExpire' => 3600,

    'BACKEND_WEB'=> '${env.projects_folder}/${env.project_folder}/backend/web/',
    'TEMP_BUILD_DIR'=> '${env.projects_folder}/${env.project_folder}/data/builds/temp/',
    'BUILD_DIR' => '${env.projects_folder}/${env.project_folder}/data/builds/',
    'DOWNLOAD_BUILD_DIR' => '${env.projects_folder}/${env.project_folder}/data/builds/',
    'TEMPLATES' => '${env.projects_folder}/${env.project_folder}/frontend/web/templates/',
    'CERTIFICATE_DIR' => '${env.projects_folder}/${env.project_folder}/data/certificates/',
    'SITE_ROOT' => realpath(dirname(__FILE__)),
    'BACKEND' => 'http://${apache.url}',
    'FRONTEND' => 'http://${env.subdomain}-front.${env.domain}',
];
