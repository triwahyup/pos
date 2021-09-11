<?php
if(getenv('APP_ENV') == 'production'){
    return [
        'developer' => ['developer'],
        'URL' => 'http://localhost/dkds/web/index.php?r=',
    ];
}
else if(getenv('APP_ENV') == 'beta'){
    return [
        'developer' => ['developer'],
        'URL' => 'http://localhost/dkds/web/index.php?r=',
    ];
}
else if(getenv('APP_ENV') == 'local'){
    return [
        'developer' => ['developer'],
        'URL' => 'http://localhost/dkds/web/index.php?r=',
    ];
}
?>