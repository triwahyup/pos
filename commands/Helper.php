<?php
namespace app\commands;

use Yii;
use yii\web\HttpException; 
use yii\web\BadRequestHttpException; // status code 400.
use yii\web\UnauthorizedHttpException; // status code 401.
use yii\web\ForbiddenHttpException; // status code 403.
use yii\web\NotFoundHttpException; // status code 404.
use yii\web\MethodNotAllowedHttpException; // status code 405.
use yii\web\NotAcceptableHttpException; // status code 406.
use yii\web\ConflictHttpException; // status code 409.
use yii\web\GoneHttpException; // status code 410.
use yii\web\UnsupportedMediaTypeHttpException; // status code 415.
use yii\web\TooManyRequestsHttpException; // status code 429.
use yii\web\ServerErrorHttpException; // status code 500.

class Helper
{
    private function errorResponse($exception)
    {
        $name = !($exception instanceof HttpException) ? 'Error Exception' : $exception->getName;
        $message = !($exception instanceof HttpException) ? $exception->getMessage() : $exception->getMessage();
        $statusCode = !($exception instanceof HttpException) ? 500 : $exception->statusCode;

        \Yii::$app->response->statusCode = $statusCode;
        return [
            'name' => $name,
            'message' => $message,
            'code' => 0,
            'statusCode' => $statusCode
        ];
    }

    public static function catchBadRequest($message=null)
    {
        return Helper::errorResponse(new BadRequestHttpException($message ? $message : 'Bad Request'));
    }

    public static function catchNotFound($message=null)
    {
        return Helper::errorResponse(new NotFoundHttpException($message ? $message : 'Request page does not exist.'));
    }

    public static function catchNotValidate($message=null)
    {
        return Helper::errorResponse(new UnauthorizedHttpException($message ? $message : 'Error Validation'));
    }

    public static function catchForbidden($message=null)
    {
        return Helper::errorResponse(new ForbiddenHttpException($message ? $message : 'You are not allowed to perform this action.'));
    }

    public static function catchInternalError($message=null)
    {
        return Helper::errorResponse(new ServerErrorHttpException($message ? $message : 'Error Exeption'));
    }

    public static function catchError($exception)
	{
		return Helper::errorResponse($exception);
	}

    public static function responseFailed($message=false, $exception)
    {
        \Yii::$app->response->statusCode = 500;
        return [
            'statusCode' => 500,
            'message' => $message ? $message : 'Error Exceptions',
            'exception' => $exception,
        ];
    }

    public static function responseValidate($message=false, $exception)
    {
        \Yii::$app->response->statusCode = 422;
        return [
            'statusCode' => 422,
            'name' => 'ValidateErrorException',
            'message' => $message ? $message : 'Error Validation',
            'exception' => $exception,
        ];
    }

    public static function responseCreated($message=false, $data)
    {
        \Yii::$app->response->statusCode = 201;
        return [
            'statusCode' => 201,
            'message' => $message ? $message : 'CREATED SUCCESSFULLY',
            'data' => $data,
        ];
    }

    public static function responseUpdated($message=false, $data)
    {
        \Yii::$app->response->statusCode = 202;
        return [
            'statusCode' => 202,
            'message' => $message ? $message : 'UPDATED SUCCESSFULLY',
            'data' => $data,
        ];
    }

    public static function responseDeleted($message=false, $data)
    {
        \Yii::$app->response->statusCode = 202;
        return [
            'statusCode' => 202,
            'message' => $message ? $message : 'DELETED SUCCESSFULLY',
            'data' => $data,
        ];
    }
}