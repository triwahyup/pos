<?php
if(getenv('APP_ENV') == 'production'){
    return [
        'developer' => ['developer'],
    ];
}
else if(getenv('APP_ENV') == 'beta'){
    return [
        'developer' => ['developer'],
    ];
}
else if(getenv('APP_ENV') == 'local'){
    return [
        'developer' => ['developer'],
    ];
}
?>