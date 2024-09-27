<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\filters\ContentNegotiator;
use yii\helpers\ArrayHelper;
use yii\web\Response;

use common\components\Recycling;
use common\components\TelegramAPI;
use common\models\Drivers;
use common\models\UserMessengerChat;

/**
 * Регистрация водителя в мобильном приложении.
 * Водитель вводит свой номер телефона, генерируется одноразовый SMS-код, водитель вводит этот код, в случае успеха API
 * возвращает ID водителя и его персональный токен. Также в случае успеха в базу записывается дата и время этого события.
 *
 * @author Roman Karkachev <post@romankarkachev.ru>
 */
class SignUpController extends \yii\rest\ActiveController
{
    public $modelClass = 'common\models\Drivers';

    /**
     * {@inheritDoc}
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['create'], $actions['view'], $actions['update'], $actions['delete']);
        return $actions;
    }

    /**
     * {@inheritDoc}
     */
    public function behaviors() {
        return [
            [
                'class' => ContentNegotiator::class,
                'only' => ['index'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * GET-запрос генерирует одноразовый SMS-код, если он не был уже сгенерирован менее 5 минут назад, по номеру телефона,.
     * переданному в параметрах. Вместе с SMS-кодом генерируется токен, который будет необходимо отправлять каждый раз
     * при запросе данных по водителю. В случае, если на счете SMS-шлюза денежные средства заканчиваются, директору
     * отправляется уведомление в Telegram.
     * POST-запрос проверяет наличие и корректность всех переданных параметров (должен передаваться номер телефона водителя
     * и одноразовый код и SMS), в случае успеха в таблице водителей проставляется дата и время подключения к мобильному
     * приложению, в качестве ответа отдается массив с ID водителя и его персональным токеном.
     *
     * @return array|bool[]
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isPost) {
            $phone = intval(Yii::$app->request->post('phone'));
            $code = Yii::$app->request->post('code');
            if (!empty($phone) && !empty($code)) {
                $model = Drivers::find()->where(['phone' => $phone, 'mob_app_sms_code' => $code])->one();
                if ($model) {
                    // проставляем дату и время успешного подключения к мобильному приложению
                    $model->updateAttributes([
                        'mob_app_joined_at' => time(),
                    ]);
                    return [
                        'result' => true,
                        'driver_id' => $model->id,
                        'driverName' => $model->representation,
                        'token' => $model->mob_app_token,
                    ];
                }
                else {
                    return [
                        'result' => false,
                        'errorMessage' => 'Недостоверные данные.',
                    ];
                }
            }
            else {
                return [
                    'result' => false,
                    'errorMessage' => Recycling::PROMPT_INVALID_PARAMS_PASSED,
                ];
            }
        }
        else {
            $phone = intval(trim(Yii::$app->request->get('phone')));
            if (!empty($phone)) {
                $model = Drivers::find()->where(['phone' => $phone])->all();
                //$model = Drivers::find()->limit(1)->all();
                if (count($model) > 0) {
                    if (count($model) == 1) {
                        // водитель успешно и однозначно идентифицирован по номеру телефона
                        $model = $model[0];

                        // проверим, отправлялось ли сообщение этому водителю уже и если отправлялось, то как давно
                        if (!empty($model->mob_app_sms_sent_at) && (time() - $model->mob_app_sms_sent_at) < 5 * 60) {
                            // прошло не более 5 минут, запросить новый SMS-код не позволяем
                            return [
                                'result' => false,
                                'errorMessage' => 'SMS уже отправлено! Вы можете повторно отправить SMS не ранее, чем ' . Yii::$app->formatter->asRelativeTime(5*60, (time() - $model->mob_app_sms_sent_at)),
                            ];
                        }

                        $balance = floatval(Yii::$app->SMSCenter->getBalance());
                        if ($balance <= 20) {
                            // уведомляем директора о том, что деньги заканчиваются
                            TelegramAPI::postRequestToApi('sendMessage', [
                                'chat_id' => UserMessengerChat::find()->select('chat_id')->where(['messenger' => UserMessengerChat::MESSENGER_TELEGRAM, 'user_id' => 1])->scalar(),
                                'text' => 'Водитель запрашивает одноразовый SMS-код, установлено, что сумма баланса достигла критического порога. Пополните, пожалуйста, счет!',
                                'parse_mode' => 'HTML',
                            ]);
                            if ($balance <= 0) {
                                // денег вообще нет, отправка невозможна
                                return [
                                    'result' => false,
                                    'errorMessage' => 'Недостаточно средств для отправки SMS водителю ' . $phone . ', который пытается зарегистрироваться в мобильном приложении.',
                                ];
                            }
                        }

                        $pinCode = Recycling::generatePinCode();
                        TelegramAPI::postRequestToApi('sendMessage', [
                            //'chat_id' => 585135740,
                            'chat_id' => 361230627,
                            'text' => 'Ваш одноразовый SMS-код: ' . $pinCode . '.', Yii::$app->params['smsCenterSenderName'],
                            'parse_mode' => 'HTML',
                        ]);
                        //$response = Yii::$app->SMSCenter->send('+79281212863', 'Ваш одноразовый SMS-код: ' . $pinCode . '.', Yii::$app->params['smsCenterSenderName']);
                        //$response = Yii::$app->SMSCenter->send('+79097276809', 'Ваш одноразовый SMS-код: ' . $pinCode . '.', Yii::$app->params['smsCenterSenderName']);
                        $response = Yii::$app->SMSCenter->send('+7' . $phone, 'Ваш одноразовый SMS-код: ' . $pinCode . '.', Yii::$app->params['smsCenterSenderName']);
                        //$response = true;
                        if (false !== $response && !ArrayHelper::keyExists('error', $response)) {
                            $model->updateAttributes([
                                'mob_app_sms_code' => $pinCode,
                                'mob_app_sms_sent_at' => time(),
                                'mob_app_token' => Yii::$app->security->generateRandomString(),
                            ]);
                            return ['result' => true];
                        }
                    }
                    else {
                        // с таким номером телефона водителей в системе несколько
                        return [
                            'result' => false,
                            'errorMessage' => 'Обнаружено несколько водителей с данным номером телефона, обратитесь в компанию.',
                        ];
                    }
                }
                else {
                    return [
                        'result' => false,
                        'errorMessage' => 'Водитель с таким номером телефона не обнаружен.',
                    ];
                }
            }
            else {
                return [
                    'result' => false,
                    'errorMessage' => Recycling::PROMPT_INVALID_PARAMS_PASSED,
                ];
            }
        }
    }
}
