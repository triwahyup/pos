<?php
if(getenv('APP_ENV') == 'production'){
    return [
        'developer' => ['admin'],
    ];
}
else if(getenv('APP_ENV') == 'beta'){
    return [
        'developer' => ['admin'],
    ];
}
else if(getenv('APP_ENV') == 'local'){
    return [
        'developer' => ['admin'],
    ];
}
?>