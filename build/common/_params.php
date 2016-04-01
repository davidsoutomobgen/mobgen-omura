<?php
return [
    'projectName'       => 'Omura',
    'projectNameShort'  => 'O',
    'adminEmail' => '${env.admin_email}',
    'supportEmail' => '${env.admin_email}',
    'user.passwordResetTokenExpire' => 3600,

    'BACKEND_WEB'=> '${env.project_folder}/backend/web/',
    'TEMP_BUILD_DIR'=> '${env.project_folder}/data/builds/temp/',
    'BUILD_DIR' => '${env.project_folder}/data/builds/',
    'DOWNLOAD_BUILD_DIR' => '${env.project_folder}/data/builds/',
    'TEMPLATES' => '${env.project_folder}/frontend/web/templates/',
    'CERTIFICATE_DIR' => '${env.project_folder}/data/certificates/',
    'SITE_ROOT' => realpath(dirname(__FILE__)),
    'FRONTEND' => 'http://${apache.url}',
];
